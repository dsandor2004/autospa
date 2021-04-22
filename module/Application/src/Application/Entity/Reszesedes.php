<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reszesedes
 *
 * @ORM\Table(name="reszesedes")
 * @ORM\Entity
 */
class Reszesedes
{    
    //A koronkai vulkanizalos munkas, aki a kozmetikazasbol kap szazalekot;
    const VULK_RESZ_MUNKAS_ID = 2;
    const VULK_RESZ_SZAZALEK = 20;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var \Application\Entity\Munkas
     * 
     * @ORM\ManyToOne(targetEntity="Application\Entity\Munkas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="munkas_id", referencedColumnName="id")
     * })
     */
    protected $munkas;

    /**
     * @var \Application\Entity\Aktivitas
     * 
     * @ORM\ManyToOne(targetEntity="Application\Entity\Aktivitas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="aktivitas_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    protected $aktivitas;
    
    /**
     * @var float
     *
     * @ORM\Column(name="reszesedes", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $reszesedes;
    
    /**
     * Initialies the roles variable.
     */
    public function __construct()
    {
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
     * Set munkas
     *
     * @param \Application\Entity\Munkas $munkas
     * @return Rendszam
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
     * Set aktivitas
     *
     * @param \Application\Entity\Aktivitas $aktivitas
     * @return Rendszam
     */
    public function setAktivitas(\Application\Entity\Aktivitas $aktivitas = null)
    {
        $this->aktivitas = $aktivitas;

        return $this;
    }

    /**
     * Get aktivitas
     *
     * @return \Application\Entity\Aktivitas 
     */
    public function getAktivitas()
    {
        return $this->aktivitas;
    }
    
    /**
     * Set reszesedes
     *
     * @param string $reszesedes
     * @return Reszesedes
     */
    public function setReszesedes($reszesedes)
    {
        $this->reszesedes = $reszesedes;

        return $this;
    }

    /**
     * Get reszesedes
     *
     * @return string 
     */
    public function getReszesedes()
    {
        return $this->reszesedes;
    }    
}
