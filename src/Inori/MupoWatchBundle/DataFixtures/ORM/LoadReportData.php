<?php

namespace Inori\MupoWatchBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Inori\MupoWatchBundle\Entity\Report;

class LoadReportData implements FixtureInterface
{
    private $_reportsArr = array(
                array('number' => '63', 'destination' => 'Maneeži', 'stopBefore' => 'Lasnamägi', 
                      'info' => '', 'datetime' => '2011-07-06 17:00', 'type' => 'bus'),
                array('number' => '51', 'destination' => 'Viru keskus', 'stopBefore' => 'Kärberi', 
                      'info' => '', 'datetime' => '2011-07-09 10:00', 'type' => 'bus'),
                array('number' => '3', 'destination' => 'Kaubamaja', 'stopBefore' => 'Tõnismägi', 
                      'info' => 'Rahvusraamatukogu juures', 'datetime' => '2011-07-10 18:30',
                      'type' => 'troll'),
                array('number' => '1', 'destination' => 'Kopli', 'stopBefore' => 'Balti jaam', 
                      'info' => '', 'datetime' => '2011-07-10 15:40', 'type' => 'tram'),
                array('number' => '23', 'destination' => 'Kaubamaja', 'stopBefore' => 'Tõnismägi', 
                      'info' => '', 'datetime' => '2011-07-08 15:00', 'type' => 'bus'),
                array('number' => '2', 'destination' => 'Ülemiste', 'stopBefore' => 'Paberi', 
                      'info' => '', 'datetime' => '2011-07-10 19:30', 'type' => 'tram'),
                array('number' => '67', 'destination' => 'Estonia', 'stopBefore' => 'Laulupeo', 
                      'info' => '', 'datetime' => '2011-07-09 11:00', 'type' => 'bus'),
                array('number' => '5', 'destination' => 'Metsakooli', 'stopBefore' => 'Vineeri', 
                      'info' => '', 'datetime' => '2011-07-10 19:00', 'type' => 'bus'),
                array('number' => '36', 'destination' => 'Viru', 'stopBefore' => 'Hallivanamehe', 
                      'info' => '', 'datetime' => '2011-07-10 12:10', 'type' => 'bus'),
                array('number' => '18', 'destination' => 'Laagri', 'stopBefore' => 'Tallinn-Väike', 
                      'info' => '', 'datetime' => '2011-07-03 17:00', 'type' => 'bus'),
        
            );
    
    public function load($em)
    {
        foreach ($this->_reportsArr as $reportArr) {
            $report = new Report();
            $report->setNumber($reportArr['number']);
            $report->setDestination($reportArr['destination']);
            $report->setStationBefore($reportArr['stopBefore']);
            $report->setInfo($reportArr['info']);
            $report->setDatetime(new \DateTime($reportArr['datetime']));
            $report->setType($reportArr['type']);
            $report->setApproved(1);

            $em->persist($report);
        }
        
        $em->flush();
    }
}