(() => {
  const LS_PREFIX = "editable:";
  const PAGE_KEY = location.origin + location.pathname;
  const FORM_ID = "edit-form";

  const tClientStart = performance.now();

  const isEditable = (el) => {
    if (!el || el.nodeType !== 1) return false;

    if (el.closest(`#${FORM_ID}`)) return false;

    if (el.closest("#collapse-editor-form")) return false;

    if (el.closest(`#collapse-editor-container`)) return false;
    if (el.closest(`#collapse-display-container`)) return false;

    if (el.closest(".block1") && !el.closest(".blockX")) return false;

    const tag = el.tagName.toLowerCase();
    if (["script", "style", "link", "meta", "head", "title"].includes(tag))
      return false;

    const r = el.getBoundingClientRect();
    return r.width > 0 && r.height > 0;
  };

  const keyFor = (el) => {
    const parts = [];
    let n = el;
    while (n && n.nodeType === 1 && n !== document.documentElement) {
      const tag = n.tagName.toLowerCase();
      const siblings = Array.from(n.parentNode.children).filter(
        (s) => s.tagName === n.tagName
      );
      const idx = siblings.indexOf(n);
      parts.unshift(`${tag}:nth(${idx})`);
      n = n.parentElement;
    }
    return `${LS_PREFIX}${PAGE_KEY}::${parts.join(" > ")}`;
  };

  const applyData = (el, data) => {
    if (!data) return;
    if (data.type === "image") {
      if (el.tagName === "IMG") {
        el.src = data.src;
        if (data.alt != null) el.alt = data.alt;
      } else {
        el.innerHTML = "";
        const img = document.createElement("img");
        img.src = data.src;
        img.alt = data.alt || "";
        img.style.maxWidth = "100%";
        img.style.maxHeight = "250px";
        img.style.display = "block";
        img.style.objectFit = "contain";
        el.appendChild(img);
      }
    } else {
      el.textContent = data.text ?? "";
    }
  };

  const form = (() => {
    const f = document.createElement("form");
    f.id = FORM_ID;
    Object.assign(f.style, {
      position: "fixed",
      zIndex: 9999,
      background: "#fff",
      border: "1px solid #ccc",
      borderRadius: "8px",
      padding: "10px",
      display: "none",
      boxShadow: "0 4px 10px rgba(0,0,0,0.1)",
      maxWidth: "560px",
      maxHeight: "70vh",
      overflow: "auto",
    });
    f.innerHTML = `
      <div style="margin-bottom:6px">
        <label><input type="radio" name="mode" value="text" checked> Текст</label>
        <label style="margin-left:10px"><input type="radio" name="mode" value="image"> Фото (URL)</label>
      </div>
      <div data-text>
        <textarea rows="3" style="width:100%;box-sizing:border-box"></textarea>
      </div>
      <div data-img style="display:none">
        <input type="url" placeholder="https://example.com/photo.jpg" style="width:100%;box-sizing:border-box">
        <input type="text" placeholder="alt-текст (необов’язково)" style="width:100%;margin-top:4px;box-sizing:border-box">
      </div>
      <div style="margin-top:8px;text-align:right">
        <button type="button" data-act="cancel">Скасувати</button>
        <button type="submit">Зберегти</button>
      </div>
    `;
    f.addEventListener("change", (e) => {
      if (e.target.name === "mode") {
        const m = e.target.value;
        f.querySelector("[data-text]").style.display =
          m === "text" ? "block" : "none";
        f.querySelector("[data-img]").style.display =
          m === "image" ? "block" : "none";
      }
    });
    document.body.appendChild(f);
    return f;
  })();

  let current = null;

  const showForm = (el) => {
    const pad = 8;
    const r = el.getBoundingClientRect();

    form.style.display = "block";
    form.style.visibility = "hidden";
    form.style.left = "0px";
    form.style.top = "0px";

    const fw = form.offsetWidth;
    const fh = form.offsetHeight;

    let left = window.scrollX + r.left + pad;
    let top = window.scrollY + r.bottom + pad;

    const vLeft = window.scrollX + pad;
    const vRight = window.scrollX + window.innerWidth - pad;
    const vTop = window.scrollY + pad;
    const vBottom = window.scrollY + window.innerHeight - pad;

    if (top + fh > vBottom) top = window.scrollY + r.top - fh - pad;
    if (left + fw > vRight) left = vRight - fw;
    if (left < vLeft) left = vLeft;
    if (top < vTop) top = vTop;

    form.style.left = `${left}px`;
    form.style.top = `${top}px`;
    form.style.visibility = "visible";
  };

  const hideForm = () => {
    form.style.display = "none";
  };

  const restoreFromDB = async () => {
    const t0 = performance.now();
    const elements = Array.from(document.querySelectorAll("body *"));
    const elementMap = new Map();
    const keys = [];

    for (const el of elements) {
      if (isEditable(el)) {
        const key = keyFor(el);
        keys.push(key);
        elementMap.set(key, el);
      }
    }

    if (keys.length === 0) {
      return { count: 0, ms: performance.now() - t0 };
    }

    try {
      const response = await fetch("load.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ keys: keys }),
      });
      const data = await response.json();

      let applied = 0;
      for (const key in data) {
        const el = elementMap.get(key);
        const content = data[key];
        if (el && content) {
          applyData(el, content);
          applied++;
        }
      }
      return { count: applied, ms: performance.now() - t0 };
    } catch (err) {
      console.error("Failed to restore from DB:", err);
      return { count: 0, ms: performance.now() - t0 };
    }
  };

  const showPerfBadge = ({ serverMs, clientGenMs, dbMs, dbCount }) => {
    const b = document.createElement("div");
    Object.assign(b.style, {
      position: "fixed",
      right: "12px",
      bottom: "12px",
      background: "rgba(17,24,39,.9)",
      color: "#fff",
      padding: "8px 10px",
      borderRadius: "8px",
      fontSize: "12px",
      lineHeight: "1.35",
      zIndex: 9998,
    });
    b.innerHTML = `
      <div><strong>Server gen:</strong> ${
        serverMs != null ? serverMs.toFixed(1) + " ms" : "—"
      }</div>
      <div><strong>Client gen:</strong> ${clientGenMs.toFixed(1)} ms</div>
      <div><strong>DB load (async):</strong> ${dbMs.toFixed(
        1
      )} ms (${dbCount} ел.)</div>`;
    document.body.appendChild(b);
  };

  document.addEventListener("DOMContentLoaded", () => {
    const clientGenMs = performance.now() - tClientStart;
    const serverMs =
      typeof window.__serverGenMs === "number" ? window.__serverGenMs : null;

    restoreFromDB().then(({ count: dbCount, ms: dbMs }) => {
      showPerfBadge({
        serverMs,
        clientGenMs,
        dbMs: dbMs,
        dbCount: dbCount,
      });
    });

    document.body.addEventListener(
      "click",
      (e) => {
        if (e.target.closest(`#${FORM_ID}`)) return;
        const el = e.target;
        if (!isEditable(el)) return;
        e.preventDefault();
        e.stopPropagation();
        current = el;

        const isImgEl = el.tagName === "IMG" || el.querySelector("img");
        form.querySelector(
          `[value="${isImgEl ? "image" : "text"}"]`
        ).checked = true;
        form.querySelector("[data-text]").style.display = isImgEl
          ? "none"
          : "block";
        form.querySelector("[data-img]").style.display = isImgEl
          ? "block"
          : "none";

        form.querySelector("textarea").value = el.textContent.trim();
        form.querySelector('[data-img] input[type="url"]').value = isImgEl
          ? el.tagName === "IMG"
            ? el.getAttribute("src") || ""
            : el.querySelector("img")?.getAttribute("src") || ""
          : "";
        form.querySelector('[data-img] input[type="text"]').value = isImgEl
          ? el.tagName === "IMG"
            ? el.getAttribute("alt") || ""
            : el.querySelector("img")?.getAttribute("alt") || ""
          : "";

        showForm(el);
      },
      true
    );

    form.addEventListener("click", (e) => {
      if (e.target.dataset.act === "cancel") {
        hideForm();
        current = null;
      }
    });

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      if (!current) return;

      const mode = form.querySelector('input[name="mode"]:checked').value;
      let dataToSave;
      let target = current;

      if (mode === "image") {
        const src = form
          .querySelector('[data-img] input[type="url"]')
          .value.trim();
        const alt = form
          .querySelector('[data-img] input[type="text"]')
          .value.trim();
        if (!src) return alert("Введіть URL зображення!");

        dataToSave = { type: "image", src, alt };
        applyData(target, dataToSave);

        fetch("save.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ key: keyFor(target), data: dataToSave }),
        });
      } else {
        const text = form.querySelector("textarea").value;
        target =
          current.tagName === "IMG"
            ? current.parentElement || current
            : current;

        dataToSave = { type: "text", text };
        applyData(target, dataToSave);

        fetch("save.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ key: keyFor(target), data: dataToSave }),
        });
      }

      hideForm();
      current = null;
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") hideForm();
    });
  });
})();
