<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Munkastipus
 *
 * @ORM\Table(name="munkastipus")
 * @ORM\Entity
 */
class Munkastipus
{    
    const MOSODAS_TIPUS_ID = 1;
    const VULKANIZALOS_TIPUS_ID = 3;
    const BAROS_TIPUS_ID = 5;
    
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
     * @ORM\Column(name="tipusnev", type="string", length=255, nullable=true)
     */
    private $tipusnev;

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
     * Set tipusnev
     *
     * @param string $tipusnev
     * @return Munkatipus
     */
    public function setTipusnev($tipusnev)
    {
        $this->tipusnev = $tipusnev;

        return $this;
    }

    /**
     * Get tipusnev
     *
     * @return string 
     */
    public function getTipusnev()
    {
        return $this->tipusnev;
    }
    
}
