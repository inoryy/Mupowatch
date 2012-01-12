<?php

namespace Inori\MupoWatchBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TransportRepository extends EntityRepository
{
    public function getTransportNumbersByType($type)
    {
        $q = $this->createQueryBuilder('trans')
                    ->select('trans.number, trans.id')
                    ->where('trans.type = ?1')
                    ->setParameters(array(1 => $type))
                    ->getQuery();
        
        return $q->getArrayResult();
    }
}