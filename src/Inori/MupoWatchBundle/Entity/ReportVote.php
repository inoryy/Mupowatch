<?php

namespace Inori\MupoWatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="report_vote")
 */
class ReportVote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $ip;
    
    /**
     * @ORM\Column(type="smallint")
     */
    protected $vote;
    
    /**
     * @ORM\ManyToOne(targetEntity="Report", inversedBy="votes")
     */
    protected $report;    

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
     * Set ip
     *
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set vote
     *
     * @param smallint $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    }

    /**
     * Get vote
     *
     * @return smallint 
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set report
     *
     * @param Inori\MupoWatchBundle\Entity\Report $report
     */
    public function setReport(\Inori\MupoWatchBundle\Entity\Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get report
     *
     * @return Inori\MupoWatchBundle\Entity\Report 
     */
    public function getReport()
    {
        return $this->report;
    }
}