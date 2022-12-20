<?php


namespace Hr\Filter;


use Laminas\Filter\Word\CamelCaseToUnderscore;

class ArrayKeysUnderscore
{
    public static function filter(array $data): array
    {
        $filter = new CamelCaseToUnderscore();

        $temp = [];
        foreach ($data as $key => $value) {
            $temp[strtolower($filter->filter($key))] = $value;
        }

        return $temp;
    }
}