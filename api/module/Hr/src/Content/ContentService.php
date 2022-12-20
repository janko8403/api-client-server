<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 02.03.2017
 * Time: 15:06
 */

namespace Hr\Content;

use Laminas\Mvc\I18n\Translator;

class ContentService implements ContentServiceInterface
{
    public function substitute(string $template, array $data): string
    {
        throw new \Exception("Method not implemented");

        foreach ($data as $key => $value) {

        }
    }
}