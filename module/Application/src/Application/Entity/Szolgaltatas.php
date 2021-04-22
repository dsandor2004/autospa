<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Szolgaltatas
 *
 * @ORM\Table(name="szolgaltatas", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_4F34FF061CE474D3", columns={"nev"})})
 * @ORM\Entity
 */
class Szolgaltatas
{
    const VIASZ_ID = 20;
	const SMA_ID = 9;
	const SMAC_ID = 10;
	const SS_ID = 11;
    
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
     * @ORM\Column(name="nev", type="string", length=255, nullable=true)
     */
    private $nev;

    /**
     * @var float
     *
     * @ORM\Column(name="alapar", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $alapar;

    /**
     * @var float
     *
     * @ORM\Column(name="cegesar", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $cegesar;



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
     * Set alapar
     *
     * @param string $alapar
     * @return Szolgaltatas
     */
    public function setAlapar($alapar)
    {
        $this->alapar = $alapar;

        return $this;
    }

    /**
     * Get alapar
     *
     * @return string 
     */
    public function getAlapar()
    {
        return $this->alapar;
    }

    /**
     * Set cegesar
     *
     * @param string $cegesar
     * @return Szolgaltatas
     */
    public function setCegesar($cegesar)
    {
        $this->cegesar = $cegesar;

        return $this;
    }

    /**
     * Get cegesar
     *
     * @return string 
     */
    public function getCegesar()
    {
        return $this->cegesar;
    }
}
