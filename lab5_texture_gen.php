<?php
header('Content-Type: image/png');
$width = 32;
$height = 32;
$img = imagecreatetruecolor($width, $height);
$bg = imagecolorallocate($img, 240, 240, 240);
$line = imagecolorallocate($img, 200, 200, 200);
$dot = imagecolorallocate($img, 100, 100, 255);
imagefill($img, 0, 0, $bg);
for ($i = 0; $i < $width; $i += 8) {
    imageline($img, $i, 0, $i, $height, $line);
    imageline($img, 0, $i, $width, $i, $line);
}
imagefilledellipse($img, 16, 16, 6, 6, $dot);
imagepng($img);
imagedestroy($img);
?>