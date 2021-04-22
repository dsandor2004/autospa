<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Juttatasok
 *
 * @ORM\Table(name="juttatasok")
 * @ORM\Entity
 */
class Juttatasok
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
     * @ORM\Column(name="ejjelipenz", type="float", precision=10, scale=0, nullable=false)
     */
    private $ejjelipenz;

    /**
     * @var float
     *
     * @ORM\Column(name="habpenz", type="float", precision=10, scale=0, nullable=false)
     */
    private $habpenz;

    /**
     * @var float
     *
     * @ORM\Column(name="reklampenz", type="float", precision=10, scale=0, nullable=false)
     */
    private $reklampenz;

    /**
     * @var float
     *
     * @ORM\Column(name="baroscsaj", type="float", precision=10, scale=0, nullable=false)
     */
    private $baroscsaj;



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
     * Set ejjelipenz
     *
     * @param float $ejjelipenz
     * @return Juttatasok
     */
    public function setEjjelipenz($ejjelipenz)
    {
        $this->ejjelipenz = $ejjelipenz;

        return $this;
    }

    /**
     * Get ejjelipenz
     *
     * @return float 
     */
    public function getEjjelipenz()
    {
        return $this->ejjelipenz;
    }

    /**
     * Set habpenz
     *
     * @param float $habpenz
     * @return Juttatasok
     */
    public function setHabpenz($habpenz)
    {
        $this->habpenz = $habpenz;

        return $this;
    }

    /**
     * Get habpenz
     *
     * @return float 
     */
    public function getHabpenz()
    {
        return $this->habpenz;
    }

    /**
     * Set reklampenz
     *
     * @param float $reklampenz
     * @return Juttatasok
     */
    public function setReklampenz($reklampenz)
    {
        $this->reklampenz = $reklampenz;

        return $this;
    }

    /**
     * Get reklampenz
     *
     * @return float 
     */
    public function getReklampenz()
    {
        return $this->reklampenz;
    }

    /**
     * Set baroscsaj
     *
     * @param float $baroscsaj
     * @return Juttatasok
     */
    public function setBaroscsaj($baroscsaj)
    {
        $this->baroscsaj = $baroscsaj;

        return $this;
    }

    /**
     * Get baroscsaj
     *
     * @return float 
     */
    public function getBaroscsaj()
    {
        return $this->baroscsaj;
    }
}
