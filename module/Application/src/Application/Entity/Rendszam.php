<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rendszam
 *
 * @ORM\Table(name="rendszam")
 * @ORM\Entity
 */
class Rendszam
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
     * @var string
     *
     * @ORM\Column(name="rendszam", type="string", length=10, nullable=false)
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rendszam
     *
     * @param string $rendszam
     * @return Rendszam
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
     * @return Rendszam
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
}
