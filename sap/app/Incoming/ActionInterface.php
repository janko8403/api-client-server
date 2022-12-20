<?php

namespace App\Incoming;

interface ActionInterface
{
    public function rules();

    public function validate(array $data): bool;

    public function execute(array $data);
}
