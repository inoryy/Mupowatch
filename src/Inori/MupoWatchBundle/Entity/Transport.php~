<?php

namespace Inori\MupoWatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Inori\MupoWatchBundle\Repository\TransportRepository")
 * @ORM\Table(name="transport")
 */
class Transport
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length="3")
     */
    protected $number; 
    
    /**
     * @ORM\Column(type="string", length="50")
     * 
     */
    protected $type;
    
    /**
     * @ORM\OneToMany(targetEntity="Destination", mappedBy="transport")
     */
    protected $destinations;
    
    public function __construct()
    {
        $this->destinations = new ArrayCollection();
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
     * Set number
     *
     * @param integer $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add destinations
     *
     * @param Inori\MupoWatchBundle\Entity\Destination $destinations
     */
    public function addDestinations(\Inori\MupoWatchBundle\Entity\Destination $destinations)
    {
        $this->destinations[] = $destinations;
    }

    /**
     * Get destinations
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getDestinations()
    {
        return $this->destinations;
    }
}