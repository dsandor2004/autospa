<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aru
 *
 * @ORM\Table(name="aruforgas")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Aruforgas extends EditableDeletableEntity
{
    const TIPUS_NORMAL = 0;
    const TIPUS_MUNKASNAK = 1;
    const TIPUS_INGYEN = 2;
	const TIPUS_LELTAR = 3;

    const TIPUS_NORMAL_STRING = "Normál";
    const TIPUS_MUNKASNAK_STRING = "Munkásnak tartozásra";
    const TIPUS_INGYEN_STRING = "Ingyen";
	const TIPUS_LELTAR_STRING = "Leltar";
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
	
    /**
     * @var \Application\Entity\Aru
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Aru")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="aru_id", referencedColumnName="id")
     * })
     */
    private $aru;
	
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */    
    private $darab;
	
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $megjegyzes;
	
    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */    
    private $tipus;
	
    /**
     * @var \Application\Entity\Tartozas
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Tartozas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tartozas_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $tartozas;

    /**
     * @var \Application\Entity\Aruforgas
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Aruforgas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */	
	private $parent;

    /**
     * @var \Application\Entity\Kavefajta
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Kavefajta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="kavefajta_id", referencedColumnName="id")
     * })
     */	
	private $kavefajta;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $created_at;
	
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
     * Get aru
     *
     * @return \Application\Entity\Aru
     */
    public function getAru()
    {
        return $this->aru;
    }
	
    /**
     * Set aru
     *
     * @param \Application\Entity\Aru $aru
     * @return Aruforgas
     */
    public function setAru(\Application\Entity\Aru $aru = null)
    {
        $this->aru = $aru;

        return $this;
    }
	
    /**
     * Set nev
     *
     * @param string $nev
     * @return Szolgaltatas
     */
    public function setNev($nev)
    {
        $this->nev = $nev;

        return $this;
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
    
	/**
	 * get TipusString
	 * @return string
	 */
    public function getTipusString()
    {
        switch ($this->getTipus()) {
            case self::TIPUS_NORMAL :
                return self::TIPUS_NORMAL_STRING;
                break;
            case self::TIPUS_MUNKASNAK :
                return self::TIPUS_MUNKASNAK_STRING;
                break;
            case self::TIPUS_INGYEN :
                return self::TIPUS_INGYEN_STRING;
                break;
            case self::TIPUS_LELTAR :
                return self::TIPUS_LELTAR_STRING;
                break;
            default :
                return '';
        }
    }
		
    /**
     * Set tartozas
     *
     * @param \Application\Entity\Tartozas $tartozas
     * @return Aruforgas
     */
    public function setTartozas(\Application\Entity\Tartozas $tartozas = null)
    {
        $this->tartozas = $tartozas;

        return $this;
    }

    /**
     * Get tartozas
     *
     * @return \Application\Entity\Tartozas 
     */
    public function getTartozas()
    {
        return $this->tartozas;
    }

    /**
     * Set parent
     *
     * @param \Application\Entity\Aruforgas $parent
     * @return Aruforgas
     */
    public function setParent(\Application\Entity\Aruforgas $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Application\Entity\Aruforgas 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set kavefajta
     *
     * @param \Application\Entity\Kavefajta $kavefajta
     * @return Aruforgas
     */
    public function setKavefajta(\Application\Entity\Kavefajta $kavefajta = null)
    {
        $this->kavefajta = $kavefajta;

        return $this;
    }

    /**
     * Get kavefajta
     *
     * @return \Application\Entity\Kavefajta 
     */
    public function getKavefajta()
    {
        return $this->kavefajta;
    }
	
    /**
     * Get arunev
     *
     * @return string
     */
    public function getAruNev()
    {
        return $this->getKavefajta() ? $this->getKavefajta()->getNev() : $this->getAru()->getNev();
    }

    /**
     * Get arunev
     *
     * @return string
     */
    public function getAruEladasiAr()
    {
        return $this->getKavefajta() ? $this->getKavefajta()->getAr() : $this->getAru()->getEladasiar();
    }
	
    /**
     * Get AruDarab
     *
     * @return integer
     */
    public function getAruDarab()
    {
        return $this->getKavefajta() ? $this->getDarab() / $this->getKavefajta()->getMennyiseg() : $this->getDarab();
    }
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
     * Get darab
     *
     * @return integer 
     */
    public function getDarab()
    {
        return $this->darab;
    }
	
	/**
	 * Set darab
	 * @param integer $darab
	 * @return Aruforgas
	 */
	public function setDarab($darab)
	{
		$this->darab = $darab;
		
		return $this;
	}

    /**
     * Get megjegyzes
     *
     * @return string 
     */
    public function getMegjegyzes()
    {
        return $this->megjegyzes;
    }
	
	/**
	 * Set megjegyzes
	 * @param string $megjegyzes
	 * @return Aruforgas
	 */
	public function setMegjegyzes($megjegyzes)
	{
		$this->megjegyzes = $megjegyzes;
		
		return $this;
	}
	
    /**
     * Set created_at
     *
     * @param \DateTime $datetime
     * @return Aruforgas
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
}
