<?php


namespace DocumentTemplates\Replacer;


use DocumentTemplates\Entity\DocumentTemplate;

class Common implements ReplacerInterface
{
    public function prepare(array $params): array
    {
//        if (empty($params['userId'])) {
//            throw new \Exception("Parameter userId not provided");
//        }
//        if (empty($params['dateFrom'])) {
//            throw new \Exception("Parameter dateFrom not provided");
//        }
//        if (empty($params['dateTo'])) {
//            throw new \Exception("Parameter dateTo not provided");
//        }

        $tags = array_map(function($e) {
            return "[$e]";
        }, [
            DocumentTemplate::TAG_CURRENT_DATE,
        ]);


        return [
            'from' => $tags,
            'to' => [
                (new \DateTime())->format('Y-m-d'), // TAG_CURRENT_DATE
            ]
        ];
    }
}