<?php

namespace Inori\MupoWatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Inori\MupoWatchBundle\Repository\ReportRepository")
 * @ORM\Table(name="report")
 */
class Report
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="integer")
     * 
     * @Assert\NotBlank()
     * @Assert\MinLength(1)
     * @Assert\Regex(pattern="{[^0]+$}", message="Number peaks olema valitud")
     * Add this after adjusting error styling Assert\Type(type="integer")
     */
    protected $number;
    
    /**
     * @ORM\Column(type="string", length="50")
     * 
     * @Assert\Choice({"bus", "troll", "tram"})
     */
    protected $type;
    
    /**
     * @ORM\Column(type="string", length="50")
     * @Assert\Regex(pattern="{[^0]+$}", message="Sihtpunkt peaks olema valitud") 
     * @Assert\NotBlank()
     */
    protected $destination;  
    
    /**
     * @ORM\Column(type="string", length="50", name="station_before")
     * @Assert\Regex(pattern="{[^0]+$}", message="Peatus peaks olema valitud") 
     * @Assert\NotBlank()
     */
    protected $stationBefore; 
    
    /**
     * @ORM\Column(type="string", length="255", nullable="true")
     */
    protected $info;
    
    /**
     * @ORM\Column(type="datetime")
     * 
     * @Assert\Time()
     */
    protected $datetime;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $approved;    
    
    /**
     * @ORM\Column(type="bigint")
     */
    protected $tweeted; 
    
    /**
     * @ORM\OneToMany(targetEntity="ReportVote", mappedBy="report")
     */
    protected $votes;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $rating;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;    
    
    public function __construct()
    {
        $this->datetime = new \DateTime();
        $this->info = '';
        $this->approved = true;
        $this->tweeted = false;
        $this->votes = new ArrayCollection();
        $this->rating = 0;
    }
    
    public function setData($data)
    {
        $this->number = $data['number'];
        $this->type = $data['type'];
        $this->stationBefore = $data['station_before'];
        $this->datetime = $data['datetime'];
        $this->destination = $data['destination'];
        //$this->info = $data['info'];
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set destination
     *
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * Get destination
     *
     * @return string $destination
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set stopBefore
     *
     * @param string $stopBefore
     */
    public function setStationBefore($stopBefore)
    {
        $this->stationBefore = $stopBefore;
    }

    /**
     * Get stopBefore
     *
     * @return string $stopBefore
     */
    public function getStationBefore()
    {
        return $this->stationBefore;
    }

    /**
     * Set info
     *
     * @param string $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * Get info
     *
     * @return string $info
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set datetime
     *
     * @param datetime $datetime
     */
    public function setDatetime($datetime)
    {
        // I need datetime field, but it's always today + some chosen time from form
        // This is very weird workaround. Thing of a better solution pls.
        if (date('G') > 6 && date('G') < 24) {
            $datetime->setDate(date('Y'), date('m'), date('d'));
        } else {
            $datetime->setDate(date('Y'), date('m'), date('d') - 1);
        }            
        

        $this->datetime = $datetime;
    }

    /**
     * Get datetime
     *
     * @return datetime $datetime
     */
    public function getDatetime()
    {
        return $this->datetime;
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
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set number
     *
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return string $number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set tweeted
     *
     * @param boolean $tweeted
     */
    public function setTweeted($tweeted)
    {
        $this->tweeted = $tweeted;
    }

    /**
     * Get tweeted
     *
     * @return boolean 
     */
    public function getTweeted()
    {
        return $this->tweeted;
    }

    /**
     * Add votes
     *
     * @param Inori\MupoWatchBundle\Entity\ReportVote $votes
     */
    public function addVotes(\Inori\MupoWatchBundle\Entity\ReportVote $votes)
    {
        $this->votes[] = $votes;
    }

    /**
     * Get votes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }
}