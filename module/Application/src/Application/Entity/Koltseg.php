<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Koltseg
 *
 * @ORM\Table(name="koltseg")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Koltseg extends EditableDeletableEntity
{
    const TIPUS_MOSODA_FIKO = 1;
    const TIPUS_MOSODA_KORONKA = 2;
    const TIPUS_VULKANIZALO_FIKO = 3;
    const TIPUS_VULKANIZALO_KORONKA = 4;
    const TIPUS_SZERVIZ_KORONKA = 5;

    const TIPUS_MOSODA_FIKO_STRING = 'Mosoda Fikó';
    const TIPUS_MOSODA_KORONKA_STRING = 'Mosoda Koronka';
    const TIPUS_VULKANIZALO_FIKO_STRING = 'Vulkanizáló Fikó';
    const TIPUS_VULKANIZALO_KORONKA_STRING = 'Vulkanizáló Koronka';
    const TIPUS_SZERVIZ_KORONKA_STRING = 'Szervíz Koronka';
    
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
     * @ORM\Column(name="koltsegnev", type="string", length=255, nullable=true)
     */
    private $koltsegnev;

    /**
     * @var float
     *
     * @ORM\Column(name="osszeg", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $osszeg;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum", type="date", nullable=false)
     */
    private $datum;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */    
    protected $tipus;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set koltsegnev
     *
     * @param string $koltsegnev
     * @return Koltseg
     */
    public function setKoltsegnev($koltsegnev)
    {
        $this->koltsegnev = $koltsegnev;

        return $this;
    }

    /**
     * Get koltsegnev
     *
     * @return string 
     */
    public function getKoltsegnev()
    {
        return $this->koltsegnev;
    }

    /**
     * Set osszeg
     *
     * @param string $osszeg
     * @return Koltseg
     */
    public function setOsszeg($osszeg)
    {
        $this->osszeg = $osszeg;

        return $this;
    }

    /**
     * Get osszeg
     *
     * @return string 
     */
    public function getOsszeg()
    {
        return $this->osszeg;
    }

    /**
     * Set datum
     *
     * @param \DateTime $datum
     * @return Koltseg
     */
    public function setDatum($datum)
    {
        $this->datum = $datum;

        return $this;
    }

    /**
     * Get datum
     *
     * @return \DateTime 
     */
    public function getDatum()
    {
        return $this->datum;
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
            case self::TIPUS_MOSODA_FIKO :
                return self::TIPUS_MOSODA_FIKO_STRING;
                break;
            case self::TIPUS_MOSODA_KORONKA :
                return self::TIPUS_MOSODA_KORONKA_STRING;
                break;
            case self::TIPUS_SZERVIZ_KORONKA :
                return self::TIPUS_SZERVIZ_KORONKA_STRING;
                break;
            case self::TIPUS_VULKANIZALO_FIKO :
                return self::TIPUS_VULKANIZALO_FIKO_STRING;
                break;
            case self::TIPUS_VULKANIZALO_KORONKA :
                return self::TIPUS_VULKANIZALO_KORONKA_STRING;
                break;
            default :
                return '';
        }
    }    
}
