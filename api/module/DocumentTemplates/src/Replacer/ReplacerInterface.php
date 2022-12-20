<?php


namespace DocumentTemplates\Replacer;


interface ReplacerInterface
{
    /**
     * Prepares replacement array.
     *
     * @param array $params
     * @return array [ from[], to[] ]
     */
    public function prepare(array $params): array;
}