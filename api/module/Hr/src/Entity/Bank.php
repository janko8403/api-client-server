<?php

namespace Hr\Entity;

class Bank
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return Bank
     */
    public function setCode(int $code): Bank
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Bank
     */
    public function setName(string $name): Bank
    {
        $this->name = $name;

        return $this;
    }
}