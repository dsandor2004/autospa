<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Auto
 *
 * @ORM\Table(name="auto")
 * @ORM\Entity
 */
class Auto
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    
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
     * @ORM\Column(name="rendszam", type="string", length=10, nullable=true)
     */
    private $rendszam;

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
     * Set rendszam
     *
     * @param string $rendszam
     * @return Auto
     */
    public function setRendszam($rendszam)
    {
        $this->rendszam = $rendszam;

        return $this;
    }

    /**
     * Get rendszam
     *
     * @return string 
     */
    public function getRendszam()
    {
        return $this->rendszam;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Auto
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
}
