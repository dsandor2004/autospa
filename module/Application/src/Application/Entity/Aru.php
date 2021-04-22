<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aru
 *
 * @ORM\Table(name="aru")
 * @ORM\Entity
 */
class Aru
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
	
	const KAVE_ID = 1;
	const CUKOR_ID = 2;
	const POHAR_ID = 3;
	const POHARFEDO_ID = 4;
	const TEJ_ID = 5;
	
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
     * @ORM\Column(type="text", nullable=false)
     */
    private $nev;

    /**
     * @var float
     *
     * @ORM\Column(name="vetelar", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $vetelar;

    /**
     * @var float
     *
     * @ORM\Column(name="eladasiar", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $eladasiar;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status = self::STATUS_ACTIVE;
	
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
     * Get nev
     *
     * @return string 
     */
    public function getNev()
    {
        return $this->nev;
    }

    /**
     * Set vetelar
     *
     * @param float $ar
     * @return Aru
     */
    public function setVetelar($ar)
    {
        $this->vetelar = $ar;

        return $this;
    }

    /**
     * Get vetelar
     *
     * @return float 
     */
    public function getVetelar()
    {
        return $this->vetelar;
    }
    /**
     * Set eladasiar
     *
     * @param float $ar
     * @return Aru
     */
    public function setEladasiar($ar)
    {
        $this->eladasiar = $ar;

        return $this;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Munkas
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
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
	
    /**
     * Get eladasiar
     *
     * @return float 
     */
    public function getEladasiar()
    {
        return $this->eladasiar;
    }	
}
