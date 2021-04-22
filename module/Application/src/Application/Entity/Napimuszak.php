<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Napimuszak
 *
 * @ORM\Table(name="napimuszak")
 * @ORM\Entity
 */
class Napimuszak
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
     * @var \DateTime
     *
     * @ORM\Column(name="datum", type="date", nullable=false)
     */
    private $datum;

    /**
     * @var \Application\Entity\Munkas
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Munkas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="munkas_id", referencedColumnName="id")
     * })
     */
    private $munkas;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=true)
     */    
    protected $hely;
    
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
     * Set datum
     *
     * @param \DateTime $datum
     * @return Napimuszak
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
     * Set munkas
     *
     * @param \Application\Entity\Munkas $munkas
     * @return Napimuszak
     */
    public function setMunkas(\Application\Entity\Munkas $munkas = null)
    {
        $this->munkas = $munkas;

        return $this;
    }

    /**
     * Get munkas
     *
     * @return \Application\Entity\Munkas 
     */
    public function getMunkas()
    {
        return $this->munkas;
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
            case \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID :
                return \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO;
                break;
            case \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID :
                return \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA;
                break;
            default :
                return '';
        }
    }
    
}
