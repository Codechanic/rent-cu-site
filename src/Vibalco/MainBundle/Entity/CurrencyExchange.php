<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CurrencyExchange
 *
 * @ORM\Table(name="currency_exchange")
 * @ORM\Entity(repositoryClass="Vibalco\MainBundle\Entity\CurrencyExchangeRepository")
 */
class CurrencyExchange
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
     * @var \DateTime
     *
     * @ORM\Column(name="retrieved_at", type="datetime")
     */
    private $retrievedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="base", type="string", length=4)
     */
    private $base;

    /**
     * @var string
     *
     * @ORM\Column(name="rates", type="text")
     */
    private $rates;


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
     * Set retrievedAt
     *
     * @param \DateTime $retrievedAt
     * @return CurrencyExchange
     */
    public function setRetrievedAt($retrievedAt)
    {
        $this->retrievedAt = $retrievedAt;
    
        return $this;
    }

    /**
     * Get retrievedAt
     *
     * @return \DateTime 
     */
    public function getRetrievedAt()
    {
        return $this->retrievedAt;
    }

    /**
     * Set base
     *
     * @param string $base
     * @return CurrencyExchange
     */
    public function setBase($base)
    {
        $this->base = $base;
    
        return $this;
    }

    /**
     * Get base
     *
     * @return string 
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set rates
     *
     * @param string $rates
     * @return CurrencyExchange
     */
    public function setRates($rates)
    {
        $this->rates = $rates;
    
        return $this;
    }

    /**
     * Get rates
     *
     * @return string 
     */
    public function getRates()
    {
        return $this->rates;
    }
}
