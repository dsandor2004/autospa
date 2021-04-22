<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialisAr
 *
 * @ORM\Table(name="specialis_ar")
 * @ORM\Entity
 */
class SpecialisAr
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
     * @ORM\Column(name="ar", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $ar;

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
     *   @ORM\JoinColumn(name="szolgaltatas_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $szolgaltatas;


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
     * Set ar
     *
     * @param string $ar
     * @return SpecialisAr
     */
    public function setAr($ar)
    {
        $this->ar = $ar;

        return $this;
    }

    /**
     * Get ar
     *
     * @return decimal 
     */
    public function getAr()
    {
        return $this->ar;
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
    
    /**
     * Set szolgaltatas
     *
     * @param \Application\Entity\Szolgaltatas $szolgaltatas
     * @return SpecialisAr
     */
    public function setSzolgaltatas(\Application\Entity\Szolgaltatas $szolgaltatas = null)
    {
        $this->szolgaltatas = $szolgaltatas;

        return $this;
    }

    /**
     * Get szolgaltatas
     *
     * @return \Application\Entity\Szolgaltatas 
     */
    public function getSzolgaltatas()
    {
        return $this->szolgaltatas;
    }
}
