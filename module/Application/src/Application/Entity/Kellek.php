<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Kellek
 *
 * @ORM\Table(name="kellek", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_70F4FBCA1CE474D3", columns={"nev"})})
 * @ORM\Entity
 */
class Kellek
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
     * @ORM\Column(name="nev", type="string", length=255, nullable=true)
     */
    private $nev;



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
     * @return Kellek
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
}
