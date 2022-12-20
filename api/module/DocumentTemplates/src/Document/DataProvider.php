<?php

namespace DocumentTemplates\Document;

use DocumentTemplates\Replacer\ReplacerInterface;

class DataProvider
{
    private array $replacers;

    public function __construct(array $replacers)
    {
        $this->replacers = $replacers;
    }

    /**
     * Replaces placeholders with data in given template.
     *
     * @param string $template
     * @param array $params
     * @return string
     */
    public function replace(string $template, array $params): string
    {
        $replace = ['from' => [], 'to' => []];

        /** @var ReplacerInterface $replacer */
        foreach ($this->replacers as $replacer) {
            $replace = array_merge_recursive($replace, $replacer->prepare($params));
        }

        return str_replace($replace['from'], $replace['to'], $template);
    }
}