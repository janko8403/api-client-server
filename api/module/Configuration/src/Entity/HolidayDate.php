<?php

namespace Configuration\Entity;

class HolidayDate
{
    private ?int $id = null;

    private ?\DateTime $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }
}