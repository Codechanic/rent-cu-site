<?php


namespace Vibalco\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment
 * @ORM\Table(name="comment")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Vibalco\FrontBundle\Entity\CommentRepository")
 * @package Vibalco\FrontBundle\Entity
 */
class Comment
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;


    /**
     * @var string
     * @ORM\Column(name="text", type="text")
     * @Assert\NotBlank
     */
    private $text;

    /**
     * @ORM\Column(name="nick", type="string", length=255)
     */
    private $nick;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     *@ORM\Column(name="rating", type="float")
     */
    private $rating;

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     *@ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;



    /**
     * @ORM\ManyToOne(targetEntity="Vibalco\MainBundle\Entity\Homestay")
     * @ORM\JoinColumn(nullable=false, name="homestay_id")
     */
    protected $homestay;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @param mixed $nick
     */
    public function setNick($nick)
    {
        $this->nick = $nick;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getHomestay()
    {
        return $this->homestay;
    }

    /**
     * @param mixed $homestay
     */
    public function setHomestay($homestay)
    {
        $this->homestay = $homestay;
    }





}