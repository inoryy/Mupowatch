<?php

namespace Inori\MupoWatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="station")
 */
class Station
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length="50")
     */
    protected $name;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Destination", inversedBy="stations")
     */
    protected $destination;

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
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set destination
     *
     * @param Inori\MupoWatchBundle\Entity\Destination $destination
     */
    public function setDestination(\Inori\MupoWatchBundle\Entity\Destination $destination)
    {
        $this->destination = $destination;
    }

    /**
     * Get destination
     *
     * @return Inori\MupoWatchBundle\Entity\Destination 
     */
    public function getDestination()
    {
        return $this->destination;
    }
}