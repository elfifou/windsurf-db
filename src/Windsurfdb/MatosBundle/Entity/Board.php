<?php
namespace Windsurfdb\MatosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Board
 *
 * @ORM\Table(name="board")
 * @ORM\Entity(repositoryClass="Windsurfdb\MatosBundle\Entity\BoardRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Board
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
     * @ORM\Column(name="modele", type="string", length=128)
     * @Assert\NotBlank()
     * @Assert\Length(max=128)
     */
    private $modele;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"modele", "annee", "version"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Windsurfdb\MatosBundle\Entity\Marque", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="annee", type="string", length=10, nullable=true)
     */
    private $annee;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=50, nullable=true)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="infos", type="text", nullable=true)
     */
    private $infos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="old_bdd", type="boolean")
     */
    private $oldBdd = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    public function __construct() {
        $this->date = new \Datetime();
        $this->images = new ArrayCollection();
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
     * Set modele
     *
     * @param string $modele
     * @return Board
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return string
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set annee
     *
     * @param string $annee
     * @return Board
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set version
     *
     * @param string $version
     * @return Board
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set infos
     *
     * @param string $infos
     * @return Board
     */
    public function setInfos($infos)
    {
        $this->infos = $infos;

        return $this;
    }

    /**
     * Get infos
     *
     * @return string
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Board
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
     * Set marque
     *
     * @param \Windsurfdb\MatosBundle\Entity\Marque $marque
     * @return Board
     */
    public function setMarque(\Windsurfdb\MatosBundle\Entity\Marque $marque)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return \Windsurfdb\MatosBundle\Entity\Marque
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Board
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
     * Set oldBdd
     *
     * @param boolean $oldBdd
     * @return Board
     */
    public function setOldBdd($oldBdd)
    {
        $this->oldBdd = $oldBdd;

        return $this;
    }

    /**
     * Get oldBdd
     *
     * @return boolean
     */
    public function getOldBdd()
    {
        return $this->oldBdd;
    }
}
