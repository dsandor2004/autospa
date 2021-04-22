<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aru
 *
 * @ORM\Table(name="kavefajta")
 * @ORM\Entity
 */
class Kavefajta
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
     * @ORM\Column(type="text", nullable=false)
     */
    private $nev;

    /**
     * @var integer
     *
     * @ORM\Column(name="mennyiseg", type="integer", nullable=false)
     */
    private $mennyiseg;

    /**
     * @var integer
     *
     * @ORM\Column(name="tejmennyiseg", type="integer", nullable=false)
     */
    private $tejmennyiseg;
	
    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     */
	private $ar;
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
     * @return Kavefajta
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
     * Set mennyiseg
     *
     * @param integer $mennyiseg
     * @return Kavefajta
     */
    public function setMennyiseg($mennyiseg)
    {
        $this->mennyiseg = $mennyiseg;

        return $this;
    }

    /**
     * Get mennyiseg
     *
     * @return integer 
     */
    public function getMennyiseg()
    {
        return $this->mennyiseg;
    }
	
    /**
     * Get tejmennyiseg
     *
     * @return integer 
     */
    public function getTejmennyiseg()
    {
        return $this->tejmennyiseg;
    }
	
    /**
     * Set tejmennyiseg
     *
     * @param integer $tejmennyiseg
     * @return Kavefajta
     */
    public function setTejmennyiseg($tejmennyiseg)
    {
        $this->tejmennyiseg = $tejmennyiseg;

        return $this;
    }
	
    /**
     * Set ar
     *
     * @param integer $ar
     * @return Aktivitas
     */
    public function setAr($ar)
    {
        $this->ar = $ar;

        return $this;
    }

    /**
     * Get ar
     *
     * @return integer 
     */
    public function getAr()
    {
        return $this->ar;
    }	
}
