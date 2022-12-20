<?php

namespace Settings\Entity;

class NotificationHour
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $day;

    /**
     * @var \DateTime
     */
    private $from;

    /**
     * @var \DateTime
     */
    private $to;

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
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * @param int $day
     * @return NotificationHour
     */
    public function setDay(int $day): NotificationHour
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFrom(): \DateTime
    {
        return $this->from;
    }

    /**
     * @param \DateTime $from
     * @return NotificationHour
     */
    public function setFrom(\DateTime $from): NotificationHour
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTo(): \DateTime
    {
        return $this->to;
    }

    /**
     * @param \DateTime $to
     * @return NotificationHour
     */
    public function setTo(\DateTime $to): NotificationHour
    {
        $this->to = $to;

        return $this;
    }
}