<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$im = new Imagick();
$imCircle = new Imagick();
$imPicShadow = new Imagick();
$draw = new ImagickDraw();

$im->readImage('./plantilla-express-completa.png');
$imCircle->readImage('./circle-cropped4.png');
$imPicShadow->readImage('./sombra_foto.png');

$imCircle->scaleImage(732, 0);

$im->compositeImage($imCircle, Imagick::COMPOSITE_DEFAULT, 66, 102);

$im->compositeImage($imPicShadow, Imagick::COMPOSITE_DEFAULT, 0, 0);

// $draw->setFillColor('black');
// $draw->setFontSize(123);
// $draw->setFont(__DIR__ . '/museosans-500-webfont.woff');

// $im->annotateImage($draw, 88, 190, 0, 'Nombre Apellido');

// $draw->setFontSize(100);
// $draw->setFont(__DIR__ . '/museosans-300-webfont.woff');

// $im->annotateImage($draw, 88, 340, 0, 'Sector de desarrollo');

// $im->annotateImage($draw, 255, 533, 0, 'Numero Fijo - Int: 000');

// $im->annotateImage($draw, 255, 670, 0, 'Lavarden 519');

// $im->annotateImage($draw, 2115, 520, 0, 'ventas@casiraghi.com.ar');

// $im->annotateImage($draw, 2115, 680, 0, 'www.casiraghi.com.ar');

header('Content-Type: image/png');

echo $im;
