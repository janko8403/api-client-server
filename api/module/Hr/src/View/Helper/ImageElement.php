<?php
/**
 * Created by PhpStorm.
 * User: domi
 * Date: 16.05.17
 * Time: 15:41
 */

namespace Hr\View\Helper;

use Hr\Form\Element\ImageElement as Image;
use Laminas\View\Helper\AbstractHelper;
use Laminas\Mvc\I18n\Translator;

class ImageElement extends AbstractHelper
{

    private static $staticFormat = <<<HTML
        <p class="form-control-static">
            <img src="%s" class="img-rounded %s" id="%s" alt="">
            </img>
        </p>
HTML;

    private static $emptyFormat = <<<HTML
            <p class="form-control-static"></p>
HTML;

    public function __invoke(Image $element)
    {
        if ($element->getAttribute('src')) {
            return sprintf(
                self::$staticFormat,
                $element->getAttribute('src'),
                $element->getAttribute('class'),
                $element->getAttribute('id')
            );
        } else {
            return sprintf(self::$emptyFormat);
        }
    }
}