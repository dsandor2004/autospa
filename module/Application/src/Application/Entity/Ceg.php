<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ceg
 *
 * @ORM\Table(name="ceg")
 * @ORM\Entity
 */
class Ceg
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
     * @ORM\Column(name="cegname", type="string", length=255, nullable=false)
     */
    private $cegname;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status = self::STATUS_ACTIVE;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="szerzodesdatum", type="date", nullable=false)
     */
    private $szerzodesdatum;

    /**
     * @var integer
     *
     * @ORM\Column(name="automatikushosszabbitas", type="smallint", nullable=false)
     */
    private $automatikushosszabbitas;

    /**
     * @var integer
     *
     * @ORM\Column(name="idotartam", type="smallint", nullable=false)
     */
    private $idotartam;

    /**
     * @var integer
     *
     * @ORM\Column(name="fizetesihatarido", type="smallint", nullable=false)
     */
    private $fizetesihatarido;

    /**
     * @var integer
     *
     * @ORM\Column(name="szazalekmunkas", type="smallint", nullable=false)
     */
    private $szazalekmunkas;


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
     * Set cegname
     *
     * @param string $cegname
     * @return Ceg
     */
    public function setCegname($cegname)
    {
        $this->cegname = $cegname;

        return $this;
    }

    /**
     * Get cegname
     *
     * @return string 
     */
    public function getCegname()
    {
        return $this->cegname;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Ceg
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
     * Set szerzodesdatum
     *
     * @param \DateTime $szerzodesdatum
     * @return Ceg
     */
    public function setSzerzodesdatum($szerzodesdatum)
    {
        $this->szerzodesdatum = $szerzodesdatum;

        return $this;
    }

    /**
     * Get szerzodesdatum
     *
     * @return \DateTime 
     */
    public function getSzerzodesdatum()
    {
        return $this->szerzodesdatum;
    }

    /**
     * Set automatikushosszabbitas
     *
     * @param integer $automatikushosszabbitas
     * @return Ceg
     */
    public function setAutomatikushosszabbitas($automatikushosszabbitas)
    {
        $this->automatikushosszabbitas = $automatikushosszabbitas;

        return $this;
    }

    /**
     * Get automatikushosszabbitas
     *
     * @return integer 
     */
    public function getAutomatikushosszabbitas()
    {
        return $this->automatikushosszabbitas;
    }

    /**
     * Set szazalekmunkas
     *
     * @param integer $szazalekmunkas
     * @return Ceg
     */
    public function setSzazalekmunkas($szazalekmunkas)
    {
        $this->szazalekmunkas = $szazalekmunkas;

        return $this;
    }

    /**
     * Get szazalekmunkas
     *
     * @return integer 
     */
    public function getSzazalekmunkas()
    {
        return $this->szazalekmunkas;
    }
    
    /**
     * Set idotartam
     *
     * @param integer $idotartam
     * @return Ceg
     */
    public function setIdotartam($idotartam)
    {
        $this->idotartam = $idotartam;

        return $this;
    }

    /**
     * Get idotartam
     *
     * @return integer 
     */
    public function getIdotartam()
    {
        return $this->idotartam;
    }

    /**
     * Set fizetesihatarido
     *
     * @param integer $fizetesihatarido
     * @return Ceg
     */
    public function setFizetesihatarido($fizetesihatarido)
    {
        $this->fizetesihatarido = $fizetesihatarido;

        return $this;
    }

    /**
     * Get fizetesihatarido
     *
     * @return integer 
     */
    public function getFizetesihatarido()
    {
        return $this->fizetesihatarido;
    }
}
