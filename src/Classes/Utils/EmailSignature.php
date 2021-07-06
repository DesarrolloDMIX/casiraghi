<?php

declare(strict_types=1);

namespace Wsi\Utils;

use Imagick;
use ImagickDraw;
use ImagickPixel;

class EmailSignature
{
    static public $theme;

    static private $htmPlaceholder = 'NOMBRE-DE-LA-IMAGEN';

    static private $imgFilename;

    static private $templatesFolder = WWW_ROOT . 'emails/signatures/templates/';

    static private $signaturesFolder = WWW_ROOT . 'emails/signatures/';

    static private $modifiers = [];

    static private $templates = [
        'hnos' => [
            'full' => 'plantilla-hnos.png',
            'no_pic' => 'plantilla-hnos-sin_foto.png',
            'no_num' => 'plantilla-hnos-sin_num_whatsapp.png',
            'no_pic-no_num' => 'plantilla-hnos-sin_foto-sin_num_whatsapp.png',
        ],
        'express' => [
            'full' => 'plantilla-express.png',
            'no_pic' => 'plantilla-express-sin_foto.png',
            'no_num' => 'plantilla-express-sin_num_whatsapp.png',
            'no_pic-no_num' => 'plantilla-express-sin_foto-sin_num_whatsapp.png',
        ]
    ];

    static private $fields = [
        'name' => [
            'pos_x' => ['+p' => 945, '-p' => 100],
            'pos_y' => 185,
            'font_size' => 123,
            'font' => 'museosans-500-webfont.woff',
        ],
        'area' => [
            'pos_x' => ['+p' => 945, '-p' => 100],
            'pos_y' => 335,
            'font_size' => 100,
            'font' => 'museosans-300-webfont.woff',
        ],
        'phone' => [
            'pos_x' => ['+p' => 1112, '-p' => 265],
            'pos_y' => ['+w' => 810, '-w' => 530],
            'font_size' => 100,
            'font' => 'museosans-300-webfont.woff',
        ],
        'address' => [
            'pos_x' => ['+p' => 1112, '-p' => 265],
            'pos_y' => 665,
            'font_size' => 100,
            'font' => 'museosans-300-webfont.woff',
        ],
        'whatsapp' => [
            'pos_x' => ['+p' => 1112, '-p' => 265],
            'pos_y' => ['+w' => 530, '-w' => 0],
            'font_size' => 100,
            'font' => 'museosans-300-webfont.woff',
        ],
        'email' => [
            'pos_x' => ['+p' => 2965, '-p' => 2115],
            'pos_y' => 525,
            'font_size' => 100,
            'font' => 'museosans-300-webfont.woff',
        ],
    ];

    static function createSignature(array $data)
    {
        $data = self::processData($data);

        $template = self::getTemplateByData($data);

        $image = self::createImage($template, $data);

        $image->writeImage(self::$signaturesFolder . self::$imgFilename);

        $htm = self::getHtmCode();

        return $htm;
    }

    private static function processData(array $data)
    {
        $hasPic = $data['pic']->getClientFilename();

        if ($hasPic) {
            self::$modifiers['p'] = '+p';
        } else {
            $data['pic'] = null;
            self::$modifiers['p'] = '-p';
        }

        $hasWhatsapp = isset($data['whatsapp']) && $data['whatsapp'] !== '';

        if ($hasWhatsapp) {
            self::$modifiers['w'] = '+w';
        } else {
            $data['whatsapp'] = null;
            self::$modifiers['w'] = '-w';
        }

        self::$theme = $data['theme'];
        unset($data['theme']);

        self::$imgFilename = $data['name'] . ' - ' . self::$theme . '.png';

        return $data;
    }

    private static function createImage(string $templateName, array $data)
    {
        $im = new Imagick(self::$templatesFolder . $templateName);
        $draw = new ImagickDraw();

        $draw->setFillColor(new ImagickPixel('black'));

        foreach ($data as $key => $value) {
            if ($value && is_string($value)) {
                $field = self::$fields[$key];

                $field['pos_y'] = is_array($field['pos_y']) ? $field['pos_y'][self::$modifiers['w']] : $field['pos_y'];
                $field['pos_x'] = is_array($field['pos_x']) ? $field['pos_x'][self::$modifiers['p']] : $field['pos_x'];

                $draw->setFontSize($field['font_size']);
                $draw->setFont(WWW_ROOT . 'font/' . $field['font']);

                $im->annotateImage($draw, $field['pos_x'], $field['pos_y'], 0, $value);
            }
            if ($key === 'pic' && $value != null) {
                $imPic = new Imagick();
                $imPicOverlay = new Imagick(self::$templatesFolder . 'pic_overlay/' . self::$theme . '.png');

                $imPic->readImageBlob($value->getStream()->getContents());

                $imPic->scaleImage(732, 0);

                $im->compositeImage($imPic, Imagick::COMPOSITE_DEFAULT, 66, 102);

                $im->compositeImage($imPicOverlay, Imagick::COMPOSITE_DEFAULT, 0, 0);
            }
        }

        $im->setFormat('png24');

        return $im;
    }

    private static function getTemplateByData(array $data)
    {
        $templates = self::$templates[self::$theme];

        if ($data['pic'] && $data['whatsapp'])
            return $templates['full'];

        else if ($data['pic'] && $data['whatsapp'] === null)
            return $templates['no_num'];

        else if ($data['pic'] === null && $data['whatsapp'])
            return $templates['no_pic'];

        else
            return $templates['no_pic-no_num'];
    }

    private static function getHtmCode()
    {
        $htmCode = file_get_contents(self::$templatesFolder . 'htm/htm' . self::$modifiers['p'] . '.htm');

        $htmCode = str_replace(self::$htmPlaceholder, self::$imgFilename, $htmCode);

        return $htmCode;
    }
}
