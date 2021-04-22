<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Autokoltseg
 *
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="autokoltseg")
 * @ORM\Entity
 */
class Autokoltseg extends EditableDeletableEntity
{
    const UZEMANYAG_TIPUS = 0;
    const EGYEB_TIPUS = 1;
    const UZEMANYAG_STRING = 'Üzemanyag';
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
     * @var \Application\Entity\Auto
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Auto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="auto_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $auto;

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
     * @return Autokoltseg
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
     * @return Autokoltseg
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
     * @return Autokoltseg
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
    
    public function getTipus()
    {
        if ($this->getKoltsegnev() == self::UZEMANYAG_STRING)
            return self::UZEMANYAG_TIPUS;
        else
            return self::EGYEB_TIPUS;
    }
    
    /**
     * Set auto
     *
     * @param \Application\Entity\Auto $auto
     * @return Auto
     */
    public function setAuto(\Application\Entity\Auto $auto = null)
    {
        $this->auto = $auto;

        return $this;
    }

    /**
     * Get auto
     *
     * @return \Application\Entity\Auto 
     */
    public function getAuto()
    {
        return $this->auto;
    }    
}
