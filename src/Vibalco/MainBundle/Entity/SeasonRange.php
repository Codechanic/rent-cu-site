<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SeasonRange
 * 
 * @ORM\Table(name="season_range")
 * @ORM\Entity
 */
class SeasonRange {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var date
     * @ORM\Column(name="start", type="date")
     * @Assert\NotNull()
     */
    private $start;

    /**
     * @var date
     * @ORM\Column(name="end", type="date")
     * @Assert\NotNull()
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="ranges")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
     */
    private $season;

    public function __construct() {
        $this->start = new \DateTime();
        $this->end = new \DateTime();
        $this->end->add(new \DateInterval("P1M"));
    }

    public function __toString() {
        return $this->start->format('d.m.Y') . ' - ' . $this->end->format('d.m.Y');
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return SeasonRange
     */
    public function setStart($start) {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return SeasonRange
     */
    public function setEnd($end) {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd() {
        return $this->end;
    }

    /**
     * Set season
     *
     * @param \Vibalco\MainBundle\Entity\Season $season
     * @return SeasonRange
     */
    public function setSeason(\Vibalco\MainBundle\Entity\Season $season = null) {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return \Vibalco\MainBundle\Entity\Season 
     */
    public function getSeason() {
        return $this->season;
    }

}
