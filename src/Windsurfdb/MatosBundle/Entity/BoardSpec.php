<?php
namespace Windsurfdb\MatosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BoardSpec
 *
 * @ORM\Table(name="board_spec")
 * @ORM\Entity(repositoryClass="Windsurfdb\MatosBundle\Entity\BoardSpecRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BoardSpec
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
     * @ORM\ManyToOne(targetEntity="Windsurfdb\MatosBundle\Entity\Board", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $board;

    /**
     * @ORM\ManyToMany(targetEntity="Windsurfdb\MatosBundle\Entity\Programme", cascade={"persist"})
     */
    private $programmes;

     /**
     * @ORM\ManyToMany(targetEntity="Windsurfdb\MatosBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="board_spec_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")}
     * )
     **/
    private $images;

    /**
     * @var string
     *
     * @ORM\Column(name="smodele", type="string", length=128, nullable=true)
     * @Assert\Length(max=128)
     */
    private $smodele;

    /**
     * @var decimal
     *
     * @ORM\Column(name="longueur", type="decimal", precision=4, scale=1, nullable=true)
     * @Assert\Type(type="numeric"),
     * @Assert\Length(max = "5")
     */
    private $longueur;

    /**
     * @var decimal
     *
     * @ORM\Column(name="largeur", type="decimal", precision=4, scale=1, nullable=true)
     * @Assert\Type(type="numeric"),
     * @Assert\Length(max = "5")
     */
    private $largeur;

    /**
     * @var smallint
     *
     * @ORM\Column(name="volume", type="smallint", nullable=true)
     * @Assert\Type(type="numeric")
     * @Assert\Length(max = "3")
     */
    private $volume;

    /**
     * @var string
     *
     * @ORM\Column(name="voile_mini", type="string", length=10, nullable=true)
     * @Assert\Length(max = "10")
     */
    private $voileMini;

    /**
     * @var string
     *
     * @ORM\Column(name="voile_maxi", type="string", length=10, nullable=true)
     * @Assert\Length(max = "10")
     */
    private $voileMaxi;

    /**
     * @var array
     *
     * @ORM\Column(name="box", type="array", nullable=true)
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max = "50")
     * })
     */
    private $box;

    /**
     * @var array
     *
     * @ORM\Column(name="fin", type="array", nullable=true)
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max = "50")
     * })
     */
    private $fin;

    /**
     * @var array
     *
     * @ORM\Column(name="techno", type="array", nullable=true)
     * @Assert\All({
     *     @Assert\Length(max = "50")
     * })
     */
    private $techno;

    /**
     * @var array
     *
     * @ORM\Column(name="poids", type="array", nullable=true)
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Type(type="numeric"),
     *     @Assert\Length(max = "5")
     * })
     */
    private $poids;

    /**
     * @var array
     *
     * @ORM\Column(name="prix", type="array", nullable=true)
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max = "7")
     * })
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="infos", type="text", nullable=true)
     */
    private $infos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    public function __construct() {
        $this->date = new \Datetime();
        $this->programmes = new ArrayCollection();
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
     * Set smodele
     *
     * @param string $smodele
     * @return BoardSpec
     */
    public function setSmodele($smodele)
    {
        $this->smodele = $smodele;

        return $this;
    }

    /**
     * Get smodele
     *
     * @return string
     */
    public function getSmodele()
    {
        return $this->smodele;
    }

    /**
     * Set longueur
     *
     * @param string $longueur
     * @return BoardSpec
     */
    public function setLongueur($longueur)
    {
        $this->longueur = $longueur;

        return $this;
    }

    /**
     * Get longueur
     *
     * @return string
     */
    public function getLongueur()
    {
        return $this->longueur;
    }

    /**
     * Set largeur
     *
     * @param string $largeur
     * @return BoardSpec
     */
    public function setLargeur($largeur)
    {
        $this->largeur = $largeur;

        return $this;
    }

    /**
     * Get largeur
     *
     * @return string
     */
    public function getLargeur()
    {
        return $this->largeur;
    }

    /**
     * Set volume
     *
     * @param string $volume
     * @return BoardSpec
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume
     *
     * @return string
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Set infos
     *
     * @param string $infos
     * @return BoardSpec
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
     * @return BoardSpec
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
     * Set board
     *
     * @param \Windsurfdb\MatosBundle\Entity\Board $board
     * @return BoardSpec
     */
    public function setBoard(\Windsurfdb\MatosBundle\Entity\Board $board)
    {
        $this->board = $board;

        return $this;
    }

    /**
     * Get board
     *
     * @return \Windsurfdb\MatosBundle\Entity\Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set techno
     *
     * @param array $techno
     * @return BoardSpec
     */
    public function setTechno($techno)
    {
        $this->techno = $techno;

        return $this;
    }

    /**
     * Get techno
     *
     * @return array
     */
    public function getTechno()
    {
        return $this->techno;
    }

    /**
     * Set poids
     *
     * @param array $poids
     * @return BoardSpec
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * Get poids
     *
     * @return array
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Set box
     *
     * @param string $box
     * @return BoardSpec
     */
    public function setBox($box)
    {
        $this->box = $box;

        return $this;
    }

    /**
     * Get box
     *
     * @return string
     */
    public function getBox()
    {
        return $this->box;
    }

    /**
     * Set fin
     *
     * @param string $fin
     * @return BoardSpec
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return string
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set prix
     *
     * @param array $prix
     * @return BoardSpec
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return array
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set voileMini
     *
     * @param string $voileMini
     * @return BoardSpec
     */
    public function setVoileMini($voileMini)
    {
        $this->voileMini = $voileMini;

        return $this;
    }

    /**
     * Get voileMini
     *
     * @return string
     */
    public function getVoileMini()
    {
        return $this->voileMini;
    }

    /**
     * Set voileMaxi
     *
     * @param string $voileMaxi
     * @return BoardSpec
     */
    public function setVoileMaxi($voileMaxi)
    {
        $this->voileMaxi = $voileMaxi;

        return $this;
    }

    /**
     * Get voileMaxi
     *
     * @return string
     */
    public function getVoileMaxi()
    {
        return $this->voileMaxi;
    }

	public function addProgramme(Programme $programme)
	{
		// Ici, on utilise l'ArrayCollection vraiment comme un tableau
		$this->programmes[] = $programme;

		return $this;
	}

	public function removeProgramme(Programme $programme)
	{
		// Ici on utilise une méthode de l'ArrayCollection, pour supprimer le programme en argument
		$this->programmes->removeElement($programme);
	}

    /**
     * Get programmes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgrammes()
    {
        return $this->programmes;
    }

    /**
     * Add images
     *
     * @param \Windsurfdb\MatosBundle\Entity\Image $images
     * @return BoardSpec
     */
    public function addImage(\Windsurfdb\MatosBundle\Entity\Image $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Windsurfdb\MatosBundle\Entity\Image $images
     */
    public function removeImage(\Windsurfdb\MatosBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    public function ajaxOutput(array $var) {
        $output = array();
        $array_accept = array(
            'id',
            'smodele',
            'volume',
            'longueur',
            'largeur',
            'fin',
            'box',
            'poids',
            'techno',
            'prix',
            'voileMini',
            'voileMaxi',
            'infos'
        );
        foreach ($var as $value) {
            if(in_array($value, $array_accept)) {
                $output[$value] = $this->{$value};
        	}
        }
    	return $output;
    }
}
