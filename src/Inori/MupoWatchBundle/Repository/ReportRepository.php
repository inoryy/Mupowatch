<?php

namespace Inori\MupoWatchBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ReportRepository extends EntityRepository
{
    public function getLatestReportsQuery()
    {
        $q = $this->createQueryBuilder('report')
                   ->select('report')
                   ->andWhere('report.approved = 1')
                   ->orderBy('report.datetime', 'DESC')
                   ->getQuery();
        
        return $q;         
    }
    
    public function getReports($params, $lim = 3)
    {
        $q = $this->createQueryBuilder('report')
                   ->select('report')
                   ->where('report.type = ?1')
                   ->andWhere('report.destination = ?2')
                   ->andWhere('report.number = ?3')
                   ->andWhere('report.approved = 1')
                   ->orderBy('report.datetime', 'DESC')
                   ->setMaxResults($lim)
                   ->setParameters(array(1 => $params['type'], 
                            2 => $params['destination'], 3 => $params['number']))
                   ->getQuery();
        
        return $q->getResult();         
    }
    
    public function getReportStats($lim = 10)
    {
        $q = $this->createQueryBuilder('report')
                 ->select('report, COUNT(report) AS ct')
                 ->where('report.tweeted != ?1')
                 ->andWhere('report.approved = ?2')
                 ->groupBy('report.stationBefore')
                 ->having('COUNT(report) >= 2')
                 ->orderBy('ct', 'desc')
                 ->setMaxResults($lim)
                 ->setParameters(array(1 => false, 2 => true))
                 ->getQuery();
        
        return $q->getResult();
    }
}