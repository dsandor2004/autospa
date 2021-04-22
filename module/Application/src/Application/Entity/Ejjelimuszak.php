<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ejjelimuszak
 *
 * @ORM\Table(name="ejjelimuszak")
 * @ORM\Entity
 */
class Ejjelimuszak
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
     * @return Ejjelimuszak
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
     * @return Ejjelimuszak
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
}
