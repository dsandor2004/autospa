<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Aktivitas
 *
 * @ORM\Table(name="aktivitas")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Aktivitas extends EditableDeletableEntity
{
    const STATUS_OPEN = 0;
    const STATUS_CLOSED = 1;
    
    const AKTIVITAS_HELY_FIKO_ID = 1;
    const AKTIVITAS_HELY_KORONKA_ID = 2;
    const AKTIVITAS_HELY_FIKO = 'Fikó';
    const AKTIVITAS_HELY_KORONKA = 'Koronka';
    
    const TIPUS_NORMAL = 0;
    const TIPUS_CEG = 1;
    const TIPUS_FIDEL = 2;
    const TIPUS_INGYEN = 3;
    const TIPUS_FIKO = 4;

    const TIPUS_NORMAL_STRING = 'Normal';
    const TIPUS_CEG_STRING = 'Fisa';
    const TIPUS_FIDEL_STRING = 'Fidel';
    const TIPUS_INGYEN_STRING = 'Ingyen';
    const TIPUS_FIKO_STRING = 'Fikónak';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nev", type="string", length=255, nullable=true)
     */
    private $nev;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     */
    private $ar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="idopont", type="date", nullable=false)
     */
    private $idopont;
    
    /**
     * @var string
     *
     * @ORM\Column(name="rendszam", type="string", length=10, nullable=true)
     */
    private $rendszam;

    /**
     * @var \Application\Entity\Ceg
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Ceg")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ceg_id", referencedColumnName="id")
     * })
     */
    private $ceg;

    /**
     * @var \Application\Entity\Szolgaltatas
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Szolgaltatas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="szolgaltatas_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $szolgaltatas;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Application\Entity\Munkas")
     * @ORM\JoinTable(name="aktivitas_munkasok",
     *      joinColumns={@ORM\JoinColumn(name="aktivitas_id", referencedColumnName="id", onDelete="CASCADE")}, 
     *      inverseJoinColumns={@ORM\JoinColumn(name="munkas_id", referencedColumnName="id")}
     * )
     */
    protected $munkasok;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */    
    protected $tipus;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */    
    protected $hely;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */    
    protected $viasz;
    
    /** Spalat motor cu aburi
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */    
    protected $sma;	
	
    /** Spalat motor cu aburi chimic
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */    
    protected $smac;	
	
    /** Spalat sasiu
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */    
    protected $ss;	
	
    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */        
    private $status;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $created_at;
    
    /**
     * @ORM\PrePersist
     */
    public function timestamp()
    {
        if (is_null($this->getCreatedAt())) {
            $this->setCreatedAt(new \DateTime());
        }
        return $this;        
    }
    
    /**
     * Set created_at
     *
     * @param \DateTime $datetime
     * @return Koltseg
     */
    public function setCreatedAt($datetime)
    {
        $this->created_at = $datetime;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }    
    
    /**
     * Initialies the roles variable.
     */
    public function __construct()
    {
        $this->munkasok = new ArrayCollection();
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
     * Set nev
     *
     * @param string $nev
     * @return Aktivitas
     */
    public function setNev($nev)
    {
        $this->nev = $nev;

        return $this;
    }

    /**
     * Get nev
     *
     * @return string 
     */
    public function getNev()
    {
        return $this->getSzolgaltatas() ? $this->getSzolgaltatas()->getNev() : $this->nev;
    }

    /**
     * Set ar
     *
     * @param integer $ar
     * @return Aktivitas
     */
    public function setAr($ar)
    {
        $this->ar = $ar;

        return $this;
    }

    /**
     * Get ar
     *
     * @return integer 
     */
    public function getAr()
    {
        return $this->ar;
    }

    /**
     * Set idopont
     *
     * @param \DateTime $idopont
     * @return Aktivitas
     */
    public function setIdopont($idopont)
    {
        $this->idopont = $idopont;

        return $this;
    }

    /**
     * Get idopont
     *
     * @return \Datetime
     */
    public function getIdopont()
    {
        return $this->idopont;
    }

    /**
     * Set rendszam
     *
     * @param string $rendszam
     * @return Aktivitas
     */
    public function setRendszam($rendszam)
    {
        $this->rendszam = $rendszam;

        return $this;
    }

    /**
     * Get rendszam
     *
     * @return string 
     */
    public function getRendszam()
    {
        return $this->rendszam;
    }

    /**
     * Set ceg
     *
     * @param \Application\Entity\Ceg $ceg
     * @return Aktivitas
     */
    public function setCeg(\Application\Entity\Ceg $ceg = null)
    {
        $this->ceg = $ceg;

        return $this;
    }

    /**
     * Get ceg
     *
     * @return \Application\Entity\Ceg 
     */
    public function getCeg()
    {
        return $this->ceg;
    }

    /**
     * Set szolgaltatas
     *
     * @param \Application\Entity\Szolgaltatas $szolgaltatas
     * @return Szolgaltatas
     */
    public function setSzolgaltatas(\Application\Entity\Szolgaltatas $szolgaltatas = null)
    {
        $this->szolgaltatas = $szolgaltatas;

        return $this;
    }

    /**
     * Get szolgaltatas
     *
     * @return \Application\Entity\sSzolgaltatas
     */
    public function getSzolgaltatas()
    {
        return $this->szolgaltatas;
    }
    
    /**
     * Set tipus
     *
     * @param integer $tipus
     */
    public function setTipus($tipus)
    {
        $this->tipus = $tipus;
    }

    /**
     * Get tipus
     *
     * @return integer 
     */
    public function getTipus()
    {
        return $this->tipus;
    }
    
    public function getTipusString()
    {
        switch ($this->getTipus()) {
            case self::TIPUS_NORMAL :
                return self::TIPUS_NORMAL_STRING;
                break;
            case self::TIPUS_CEG :
                return self::TIPUS_CEG_STRING;
                break;
            case self::TIPUS_FIDEL :
                return self::TIPUS_FIDEL_STRING;
                break;
            case self::TIPUS_INGYEN :
                return self::TIPUS_INGYEN_STRING;
                break;
            default :
                return '';
        }
    }

    /**
     * Set hely
     *
     * @param integer $hely
     */
    public function setHely($hely)
    {
        $this->hely = $hely;
    }

    /**
     * Get hely
     *
     * @return integer 
     */
    public function getHely()
    {
        return $this->hely;
    }
    
    public function getHelyString()
    {
        switch ($this->getHely()) {
            case self::AKTIVITAS_HELY_FIKO_ID :
                return self::AKTIVITAS_HELY_FIKO;
                break;
            case self::AKTIVITAS_HELY_KORONKA_ID :
                return self::AKTIVITAS_HELY_KORONKA;
                break;
            default :
                return '';
        }
    }
    
    /**
     * Get munkas.
     *
     * @return array
     */
    public function getMunkasok()
    {
        if ($this->munkasok)
            return $this->munkasok->getValues();
        else
            return null;
    }

    /**
     * Add a munkas to the aktivitas.
     *
     * @param Munkas $munkas
     *
     * @return void
     */
    public function addMunkas($munkas)
    {
        $this->munkasok[] = $munkas;
    }
    
    /**
     * 
     * @return type
     */
    public function getMunkasObjects()
    {
        return $this->munkasok;
    }
    
    public function getMunkasokString()
    {
        $munkasArray = array();
        foreach ($this->getMunkasok() as $munkas) {
            $munkasArray[] = '<nobr>' . $munkas->getName() . '</nobr>';
        }
        
        return implode('<br />', $munkasArray);
    }
            
    /**
     * Set viasz
     *
     * @param integer $viasz
     */
    public function setviasz($viasz)
    {
        $this->viasz = $viasz;
    }

    /**
     * Get viasz
     *
     * @return integer 
     */
    public function getViasz()
    {
        return $this->viasz;
    }

    /**
     * Set Spalat motor cu aburi
     *
     * @param integer $sma
     */
    public function setSma($sma)
    {
        $this->sma = $sma;
    }

    /**
     * Get Spalat motor cu aburi
     *
     * @return integer 
     */
    public function getSma()
    {
        return $this->sma;
    }

    /**
     * Set Spalat motor cu aburi chimic
     *
     * @param integer $smac
     */
    public function setSmac($smac)
    {
        $this->smac = $smac;
    }

    /**
     * Get Spalat motor cu aburi chimic
     *
     * @return integer 
     */
    public function getSmac()
    {
        return $this->smac;
    }
	
    /**
     * Set Spalat sasiu
     *
     * @param integer $ss
     */
    public function setSs($ss)
    {
        $this->ss = $ss;
    }

    /**
     * Get Spalat sasiu
     *
     * @return integer 
     */
    public function getSs()
    {
        return $this->ss;
    }
	
    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }    
}
