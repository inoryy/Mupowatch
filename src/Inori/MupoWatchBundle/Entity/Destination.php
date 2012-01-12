<?php

namespace Inori\MupoWatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="destination")
 */
class Destination
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
     * @ORM\OneToMany(targetEntity="Station", mappedBy="destination")
     */
    protected $stations; 
    
    /**
     * @ORM\ManyToOne(targetEntity="Transport", inversedBy="destinations")
     */
    protected $transport;    
    
    public function __construct()
    {
        $this->stations = new ArrayCollection();
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
     * Add stations
     *
     * @param Inori\MupoWatchBundle\Entity\Station $stations
     */
    public function addStations(\Inori\MupoWatchBundle\Entity\Station $stations)
    {
        $this->stations[] = $stations;
    }

    /**
     * Get stations
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getStations()
    {
        return $this->stations;
    }

    /**
     * Set transport
     *
     * @param Inori\MupoWatchBundle\Entity\Transport $transport
     */
    public function setTransport(\Inori\MupoWatchBundle\Entity\Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Get transport
     *
     * @return Inori\MupoWatchBundle\Entity\Transport 
     */
    public function getTransport()
    {
        return $this->transport;
    }
}