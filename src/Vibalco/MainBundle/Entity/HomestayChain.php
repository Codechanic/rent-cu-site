<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HomestayChain
 *
 * @ORM\Table(name="homestay_chain")
 * @ORM\Entity
 */
class HomestayChain
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Season") 
     * @ORM\JoinTable(name="homestay_chain_season")
     */
    protected $seasons;

    public function __construct() {
        $this->seasons = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Add seasons
     *
     * @param \Vibalco\MainBundle\Entity\Season $seasons
     * @return HomestayChain
     */
    public function addSeason(\Vibalco\MainBundle\Entity\Season $seasons)
    {
        $this->seasons[] = $seasons;
    
        return $this;
    }

    /**
     * Remove seasons
     *
     * @param \Vibalco\MainBundle\Entity\Season $seasons
     */
    public function removeSeason(\Vibalco\MainBundle\Entity\Season $seasons)
    {
        $this->seasons->removeElement($seasons);
    }

    /**
     * Get seasons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeasons()
    {
        return $this->seasons;
    }
}
