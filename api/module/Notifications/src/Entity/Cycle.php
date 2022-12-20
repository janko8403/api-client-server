<?php

namespace Notifications\Entity;

class Cycle
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var int|null
     */
    private $delay;

    /**
     * @var int|null
     */
    private $display;

    /**
     * @var int|null
     */
    private $rankingFrom;

    /**
     * @var int|null
     */
    private $rankingTo;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getDelay(): ?int
    {
        return $this->delay;
    }

    /**
     * @param int|null $delay
     * @return Cycle
     */
    public function setDelay(?int $delay): Cycle
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDisplay(): ?int
    {
        return $this->display;
    }

    /**
     * @param int|null $display
     * @return Cycle
     */
    public function setDisplay(?int $display): Cycle
    {
        $this->display = $display;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRankingFrom(): ?int
    {
        return $this->rankingFrom;
    }

    /**
     * @param int|null $rankingFrom
     * @return Cycle
     */
    public function setRankingFrom(?int $rankingFrom): Cycle
    {
        $this->rankingFrom = $rankingFrom;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRankingTo(): ?int
    {
        return $this->rankingTo;
    }

    /**
     * @param int|null $rankingTo
     * @return Cycle
     */
    public function setRankingTo(?int $rankingTo): Cycle
    {
        $this->rankingTo = $rankingTo;

        return $this;
    }
}