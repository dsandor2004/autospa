<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AktivitasKellek
 *
 * @ORM\Table(name="aktivitas_kellek")
 * @ORM\Entity
 */
class AktivitasKellek
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
     * @var \Application\Entity\Aktivitas
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Aktivitas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="aktivitas_id", referencedColumnName="id")
     * })
     */
    private $aktivitas;

    /**
     * @var \Application\Entity\Kellek
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Kellek")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="kellek_id", referencedColumnName="id")
     * })
     */
    private $kellek;

    /**
     * @var \Application\Entity\Munkas
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Munkas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="munkas_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $munkas;
 
    /**
     * Set aktivitas
     *
     * @param \Application\Entity\Aktivitas $aktivitas
     * @return Aktivitas
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
     * Set kellek
     *
     * @param \Application\Entity\Kellek $kellek
     * @return Aktivitas
     */
    public function setKellek(\Application\Entity\Kellek $kellek = null)
    {
        $this->kellek = $kellek;

        return $this;
    }

    /**
     * Get kellek
     *
     * @return \Application\Entity\Kellek
     */
    public function getKellek()
    {
        return $this->kellek;
    }
    
    /**
     * Set munkas
     *
     * @param \Application\Entity\Munkas $munkas
     * @return Aktivitas
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
