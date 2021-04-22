<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Teendo
 *
 * @ORM\Table(name="teendo")
 * @ORM\Entity
 */
class Teendo
{
    const STATUS_ACTIVE = 1;
    const STATUS_DONE = 2;
    
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
    private $description;

    /**
     * @var \SamUser\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\SamUser\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $user;
    
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
     * Set description
     *
     * @param string $description
     * @return string
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set user
     *
     * @param \SamUser\Entity\User $user
     * @return Teendo
     */
    public function setUser(\SamUser\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SamUser\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
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
}
