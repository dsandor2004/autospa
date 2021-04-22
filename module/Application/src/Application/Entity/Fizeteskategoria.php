<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fizeteskategoria
 *
 * @ORM\Table(name="fizeteskategoria")
 * @ORM\Entity
 */
class Fizeteskategoria
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
     * @var integer
     *
     * @ORM\Column(name="minregiseg_ho", type="smallint", nullable=false)
     */
    private $minregisegHo;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxregiseg_ho", type="smallint", nullable=true)
     */
    private $maxregisegHo;

    /**
     * @var float
     *
     * @ORM\Column(name="fizetes", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $fizetes;

    /**
     * @var float
     *
     * @ORM\Column(name="szazalek", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $szazalek;

    /**
     * @var \Application\Entity\Munkastipus
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Munkastipus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="munkastipus_id", referencedColumnName="id")
     * })
     */
    private $munkastipus;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
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
     * Set minregisegHo
     *
     * @param integer $minregisegHo
     * @return Fizeteskategoria
     */
    public function setMinregisegHo($minregisegHo)
    {
        $this->minregisegHo = $minregisegHo;

        return $this;
    }

    /**
     * Get minregisegHo
     *
     * @return integer 
     */
    public function getMinregisegHo()
    {
        return $this->minregisegHo;
    }

    /**
     * Set maxregisegHo
     *
     * @param integer $maxregisegHo
     * @return Fizeteskategoria
     */
    public function setMaxregisegHo($maxregisegHo)
    {
		if ($maxregisegHo == "")
			$maxregisegHo = NULL;
        $this->maxregisegHo = $maxregisegHo;

        return $this;
    }

    /**
     * Get maxregisegHo
     *
     * @return integer 
     */
    public function getMaxregisegHo()
    {
        return $this->maxregisegHo;
    }

    /**
     * Set fizetes
     *
     * @param string $fizetes
     * @return Fizeteskategoria
     */
    public function setFizetes($fizetes)
    {
        $this->fizetes = $fizetes;

        return $this;
    }

    /**
     * Get fizetes
     *
     * @return string 
     */
    public function getFizetes()
    {
        return $this->fizetes;
    }

    /**
     * Set szazalek
     *
     * @param string $szazalek
     * @return Fizeteskategoria
     */
    public function setSzazalek($szazalek)
    {
        $this->szazalek = $szazalek;

        return $this;
    }

    /**
     * Get szazalek
     *
     * @return string 
     */
    public function getSzazalek()
    {
        return (float)$this->szazalek;
    }
    
    /**
     * Set munkastipus
     *
     * @param \Application\Entity\Munkastipus $munkastipus
     * @return Rendszam
     */
    public function setMunkastipus(\Application\Entity\Munkastipus $munkastipus = null)
    {
        $this->munkastipus = $munkastipus;

        return $this;
    }

    /**
     * Get munkastipus
     *
     * @return \Application\Entity\Munkastipus 
     */
    public function getMunkastipus()
    {
        return $this->munkastipus;
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
