<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Koltseg
 *
 * @ORM\Table(name="ugyintezofizetes")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class UgyintezoFizetes
{    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
}
