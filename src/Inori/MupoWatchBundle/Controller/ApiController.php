<?php

namespace Inori\MupoWatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * General API controller for accessing various inforamtion from database
 *
 * @Route("/_api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/reports", name="api_reports")
     */
    public function getReportsAction(Request $request)
    {
        $repo = $this->get('doctrine')->getEntityManager()->getRepository('InoriMupoWatchBundle:Report');
        $qb = $repo->createQueryBuilder('r');
        $qb->andWhere('r.approved = true')
           ->orderBy('r.createdAt')
        ;
        if ($request->query->has('from')) {
            $qb->andWhere('r.createdAt >= :from')
               ->setParameter('from', new \DateTime($request->query->get('from')));
        }

        $results = $qb->getQuery()->execute();

        $reports = array();
        foreach ($results as $result) {
            /* @var $result \Inori\MupoWatchBundle\Entity\Report */

            $reports[] = array(
                'datetime'       => $result->getDatetime()->format('d-m-y H:i:s'),
                'rating'         => $result->getRating(),
                'destination'    => $result->getDestination(),
                'station_before' => $result->getStationBefore(),
                'type'           => $result->getType(),
                'number'         => $result->getNumber()
            );
        }

        return new Response(json_encode($reports));
    }

    /**
     * @Route("/transports", name="api_transports")
     */
    public function getTransportsAction(Request $request)
    {
        $repo = $this->get('doctrine')->getEntityManager()->getRepository('InoriMupoWatchBundle:Transport');
        $qb = $repo->createQueryBuilder('t');
        if ($request->query->has('type')) {
            $qb->andWhere('t.type = :type')
               ->setParameter('type', $request->query->get('type'));
        }

        $results = $qb->getQuery()->execute();

        $transports = array();
        foreach ($results as $transportEntity) {
            /* @var $transportEntity \Inori\MupoWatchBundle\Entity\Transport */

            $transport = array(
                'number' => $transportEntity->getNumber(),
                'type'   => $transportEntity->getType()
            );
            foreach ($transportEntity->getDestinations() as $destination) {
                $transport['destinations'][] = $destination->getName();
            }

            $transports[] = $transport;
        }

        return new Response(json_encode($transports));
    }

    /**
     * @Route("/stations", name="api_stations")
     */
    public function getStationsAction(Request $request)
    {
        $repo = $this->get('doctrine')->getEntityManager()->getRepository('InoriMupoWatchBundle:Station');
        $qb = $repo->createQueryBuilder('st');
        $qb->innerJoin('st.destination', 'dest')
           ->innerJoin('dest.transport', 't');
        if ($request->query->has('type')) {
            $qb->andWhere('t.type = :type')
               ->setParameters(array(
                   'type' => $request->query->get('type')
            ));
        }
        if ($request->query->has('number')) {
            $qb->andWhere('t.number = :number')
               ->setParameters(array(
                   'number' => $request->query->get('number')
            ));
        }

        $results = $qb->getQuery()->execute();

        $stations = array();
        foreach ($results as $stationEntity) {
            /* @var $stationEntity \Inori\MupoWatchBundle\Entity\Station */

            $station = array(
                'name'      => $stationEntity->getName(),
                'transport' => array(
                    'number' => $stationEntity->getDestination()->getTransport()->getNumber(),
                    'type'   => $stationEntity->getDestination()->getTransport()->getType()
                )
            );

            $stations[] = $station;
        }

        return new Response(json_encode($stations));
    }
}