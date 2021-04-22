<?php

namespace Application\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Munkas
 *
 * @ORM\Table(name="munkas")
 * @ORM\Entity
 */
class Munkas
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hiredate", type="date", nullable=false)
     */
    private $hiredate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date", nullable=false)
     */
    private $birthdate;
    
    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $fizetes;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status = self::STATUS_ACTIVE;


    /**
     * @var \Application\Entity\Munkastipus
     *
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Munkastipus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="munkastipus_id", referencedColumnName="id")
     * })
     */
    private $munkastipus;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */    
    protected $hely;
    
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
     * Set name
     *
     * @param string $name
     * @return Munkas
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set hiredate
     *
     * @param \DateTime $hiredate
     * @return Munkas
     */
    public function setHiredate($hiredate)
    {
        $this->hiredate = $hiredate;

        return $this;
    }

    /**
     * Get hiredate
     *
     * @return \DateTime 
     */
    public function getHiredate()
    {
        return $this->hiredate;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return Munkas
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
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
     * Set hely
     *
     * @param integer $hely
     */
    public function setHely($hely)
    {
        $this->hely = $hely;
    }

    /**
     * Get hely
     *
     * @return integer 
     */
    public function getHely()
    {
        return $this->hely;
    }
    
    public function getHelyString()
    {
        switch ($this->getHely()) {
            case \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID :
                return \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO;
                break;
            case \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID :
                return \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA;
                break;
            default :
                return '';
        }
    }
    
    public function getRegisegHo($from)
    {
        $from_regiseg = \DateTime::createFromFormat('Y-m-d', $from);
        
        return $this->getHiredate()->diff($from_regiseg)->m + ($this->getHiredate()->diff($from_regiseg)->y*12);
    }
        
    /**
     * Set munkastipus
     *
     * @param \Application\Entity\Munkastipus $munkastipus
     * @return Rendszam
     */
    public function setMunkastipus(\Application\Entity\Munkastipus $munkastipus = null)
    {
        $this->munkastipus = $munkastipus;

        return $this;
    }

    /**
     * Get munkastipus
     *
     * @return \Application\Entity\Munkastipus 
     */
    public function getMunkastipus()
    {
        return $this->munkastipus;
    }
    
    /**
     * Set fizetes
     *
     * @param integer $fizetes
     * @return Munkas
     */
    public function setFizetes($fizetes)
    {
        $this->fizetes = $fizetes;

        return $this;
    }

    /**
     * Get fizetes
     *
     * @return float
     */
    public function getFizetes()
    {
        return $this->fizetes;
    }

    /**
     * Get fix fizetes
     *
     * @return float
     */
    public function getFixFizetes(ObjectManager $objectManager, $from, $hely_id)
    {
        if ((float)$this->getFizetes())
            return $this->getFizetes();
        else {
            $fizeteskategoria = $this->getFizetesKategoria($objectManager, $from, $hely_id);
            return $fizeteskategoria->getFizetes();
        }
    }
    
    public function getFizetesKategoria(ObjectManager $objectManager, $from, $hely_id)
    {
        if (!$hely_id)
            $hely_id = $this->getHely();
        
        $qb = $objectManager->getRepository('\Application\Entity\Fizeteskategoria')->createQueryBuilder('f')
                ->select('f')
                ->where("f.hely = $hely_id AND f.munkastipus = " . $this->getMunkastipus()->getId() . " AND f.minregisegHo <= " . $this->getRegisegHo($from) . " AND (f.maxregisegHo IS NULL OR f.maxregisegHo > " . $this->getRegisegHo($from) . ") ");
        $fizeteskategoria = $qb->getQuery()->getResult();
        
        return $fizeteskategoria[0];
    }
    
    public function getReszesedes(ObjectManager $objectManager, $from, $to)
    {
        $qb = $objectManager->getRepository('\Application\Entity\Reszesedes')->createQueryBuilder('r')
                ->select('SUM(r.reszesedes)')
                ->join('r.aktivitas', 'a')
                ->where("r.munkas = " . $this->getId() . " AND a.idopont >= '" . $from . "' AND a.idopont <= '" . $to . "'");
        $reszesedes = $qb->getQuery()->getSingleScalarResult();
        
        return (float)$reszesedes;
    }

    public function getEjjeliMuszakok(ObjectManager $objectManager, $from, $to)
    {        
        $qb = $objectManager->getRepository('\Application\Entity\Ejjelimuszak')->createQueryBuilder('r')
                ->select('COUNT(r)')
                ->where("r.munkas = " . $this->getId() . " AND r.datum >= '" . $from . "' AND r.datum <= '" . $to . "'");
        $ejjelimuszakok = $qb->getQuery()->getSingleScalarResult();
        
        return $ejjelimuszakok;
    }

    public function getNapiMuszakCountByHely(ObjectManager $objectManager, $from, $to, $hely_id = '')
    {        
        if ($hely_id)
            $helyString = " AND r.hely = " . $hely_id;
        else
            $helyString = '';
        
        $qb = $objectManager->getRepository('\Application\Entity\Napimuszak')->createQueryBuilder('r')
                ->select('COUNT(r)')
                ->where("r.munkas = " . $this->getId() . " AND r.datum >= '" . $from . "' AND r.datum <= '" . $to . "'" . $helyString);
        $ejjelimuszakok = $qb->getQuery()->getSingleScalarResult();
        
        return $ejjelimuszakok;
    }
    
    public function getLezartTartozasok(ObjectManager $objectManager, $from, $to)
    {
        $qb = $objectManager->getRepository('\Application\Entity\Tartozas')->createQueryBuilder('t')
                ->select('t')
                ->where("t.status = ".\Application\Entity\Tartozas::STATUS_CLOSED." AND t.datum >= '" . $from . "' AND t.datum <= '" . $to . "'");
        return $qb->getQuery()->getResult();
    }    
}
