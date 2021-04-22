<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tartozas
 *
 * @ORM\Table(name="tartozas")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Tartozas extends EditableDeletableEntity
{
    const STATUS_OPEN = 1;
    const STATUS_CLOSED = 0;
    
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
     * @ORM\Column(name="tartozasnev", type="string", length=255, nullable=true)
     */
    private $tartozasnev;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tartozasnev
     *
     * @param string $tartozasnev
     * @return Tartozas
     */
    public function setTartozasnev($tartozasnev)
    {
        $this->tartozasnev = $tartozasnev;

        return $this;
    }

    /**
     * Get tartozasnev
     *
     * @return string 
     */
    public function getTartozasnev()
    {
        return $this->tartozasnev;
    }

    /**
     * Set osszeg
     *
     * @param string $osszeg
     * @return Tartozas
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
     * @return Tartozas
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
     * @return Tartozas
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
