<?php


namespace Hr\Doctrine\Hydrator;

use Doctrine\Common\Collections\Collection;

class Common
{
    public function extract($fields, $object)
    {
        $data = [];

        foreach ($fields as $key => $field) {
            if (is_array($field)) {
                // nested values

                $value = $this->getValue($key, $object);
                if ($value instanceof Collection) {
                    foreach ($value as $item) {
                        $data[$key][] = $this->extract($field, $item);
                    }
                } else {
                    // entity
                    $data[$key] = $this->extract($field, $value);
                }
            } else {
                // simple value
                $value = $this->getValue($field, $object);
                $data[$field] = $value;
            }
        }

        return $data;
    }

    private function getValue($field, $object)
    {
        $method = 'get' . ucfirst($field);

        if (method_exists($object, $method)) {
            return call_user_func_array([$object, $method], []);
        } else {
            return null;
        }
    }
}