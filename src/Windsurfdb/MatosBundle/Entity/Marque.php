<?php
namespace Windsurfdb\MatosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Marque
 *
 * @ORM\Table(name="marque")
 * @ORM\Entity(repositoryClass="Windsurfdb\MatosBundle\Entity\MarqueRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="name", message="Cette marque existe déjà")
 */
class Marque
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
     * @ORM\Column(name="name", type="string", length=128, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=128)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var array
     *
     * @ORM\Column(name="type", type="simple_array")
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"ws", "wb", "ws,wb"}, multiple=true, message = "Choisir un type valide.")
     */
    private $type;

    /**
     * @var array
     *
     * @ORM\Column(name="url", type="array", nullable=true)
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Url()
     * })
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="exist", type="boolean")
     */
    private $exist = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;


    public function __construct() {
        $this->date = new \Datetime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate() {
        $this->setDate(new \Datetime());
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
     * Set name
     *
     * @param string $name
     * @return Marque
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param array $type
     * @return Marque
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return array
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Marque
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set exist
     *
     * @param boolean $exist
     * @return Marque
     */
    public function setExist($exist)
    {
        $this->exist = $exist;

        return $this;
    }

    /**
     * Get exist
     *
     * @return boolean
     */
    public function getExist()
    {
        return $this->exist;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Marque
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Marque
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set url
     *
     * @param array $url
     * @return Marque
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return array
     */
    public function getUrl()
    {
        return $this->url;
    }
}
