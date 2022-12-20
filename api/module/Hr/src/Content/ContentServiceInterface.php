<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 02.03.2017
 * Time: 15:33
 */

namespace Hr\Content;


interface ContentServiceInterface
{
    /**
     * Substitutes tags in given template with provided data.
     *
     * @param string $template
     * @param array  $data
     * @return string
     */
    public function substitute(string $template, array $data): string;
}