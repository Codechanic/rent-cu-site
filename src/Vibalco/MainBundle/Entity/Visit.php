<?php

namespace Vibalco\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Visit
 *
 * @ORM\Entity(repositoryClass="Vibalco\MainBundle\Repository\VisitRepository")
 * @ORM\Table(name="rcu_visit", uniqueConstraints={ @ORM\UniqueConstraint(columns={ "url", "ip"}) })
 */
class Visit
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="entity_class", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $entityclass;

    /**
     * @ORM\Column(name="entity_id", type="integer")
     * @Assert\NotNull()
     */
    private $entityid;

    /**
     * @ORM\Column(name="update_date", type="datetime")
     */
    private $updateDate;
   
    /**
     * @ORM\Column
     * @Assert\NotBlank
     */
    private $url;
    
    /**
     * @ORM\Column
     * @Assert\Ip
     */
    private $ip;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    public function __construct($entityclass, $entityid, $url, $ip) 
    {
        $this->entityclass = $entityclass;
        $this->entityid = $entityid;
        $this->url = $url;
        $this->ip = $ip;
        
        $this->updateDate = new \DateTime('now');        
        $this->count = 1;
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
     *  @Assert\IsTrue ()
     */
    public function isAClass() {
        return class_exists($this->entityclass);;
    }
    
    /**
     * Set entityclass
     *
     * @param string $entityclass
     * @return Visit
     */
    public function setEntityClass($entityclass)
    {
        $this->entityclass = $entityclass;
    
        return $this;
    }

    /**
     * Get entityclass
     *
     * @return string 
     */
    public function getEntityClass()
    {
        return $this->entityclass;
    }

    /**
     * Set entityid
     *
     * @param integer $entityid
     * @return Visit
     */
    public function setEntityId($entityid)
    {
        $this->entityid = $entityid;
    
        return $this;
    }

    /**
     * Get entityid
     *
     * @return integer 
     */
    public function getEntityId()
    {
        return $this->entityid;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Visit
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return Visit
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
    
        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }
    
    public function incCount() 
    {
        $this->count++;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Visit
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Visit
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }
}