<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 19.11.2018
 * Time: 15:35
 */

namespace Notifications\ContentProvider;

interface ContentProviderInterface
{
    public function process(string $content, array $params);
}