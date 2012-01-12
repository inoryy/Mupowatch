<?php

namespace Inori\MupoWatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/ajax")
 */
class AJAXController extends Controller
{
    /**
     * @Route("/transports", name="ajax_transport")
     */       
    public function getTransportsAction(Request $request)
    {
        $em = $this->get('doctrine')->getEntityManager();
        $transports = $em->getRepository('InoriMupoWatchBundle:Transport')
                         ->getTransportNumbersByType($request->get('type'));

        return new Response(json_encode($transports)); 
    }
    
    /**
     * @Route("/destinations", name="ajax_destination")
     */       
    public function getDestinationsAction(Request $request)
    {
        $em = $this->get('doctrine')->getEntityManager();
        
        $destinations = $em->getRepository('InoriMupoWatchBundle:Destination')
                         ->createQueryBuilder('dest')
                         ->select('dest.name, dest.id')
                         ->where('dest.transport = ?2')
                         ->setParameters(array(2 => $request->get('transport')))
                         ->getQuery()
                         ->getArrayResult();
                         //->getTransportDestinations(array($request->get('type'), $request->get('number')));

        return new Response(json_encode($destinations)); 
    }    
    
    /**
     * @Route("/stations", name="ajax_station")
     */       
    public function getStationsAction(Request $request)
    {
        $em = $this->get('doctrine')->getEntityManager();
        
        $stations = $em->getRepository('InoriMupoWatchBundle:Station')
                         ->createQueryBuilder('station')
                         ->select('station.name, station.id')
                         ->where('station.destination = ?2')
                         ->setParameters(array(2 => $request->get('destination')))
                         ->getQuery()
                         ->getArrayResult();
                         //->getTransportDestinations(array($request->get('type'), $request->get('number')));

        return new Response(json_encode($stations)); 
    }
    
    /**
     * @Route("/vote", name="ajax_vote")
     */
    public function voteAction(Request $request)
    {
        $request->trustProxyData();
        $em = $this->get('doctrine')->getEntityManager();
        $ta = $this->get('twitter_app');
        $translator = $this->get('translator');
        $report = $em->getRepository('InoriMupoWatchBundle:Report')->find($request->get('id'));
        if ($report) {
            $ip = $request->getClientIp($proxy = true);
            $votes = $report->getVotes();
            $action = $request->get('action');

            $ips = array();
            foreach ($votes as $vote) {
                $ips[] = $vote->getIp();
            }

            if (!in_array($ip, $ips)) {
                $report->setRating($report->getRating() + $action);
                if ($report->getRating() >= 5) {
                    if (!$report->getTweeted()) {
                        // Tweet
                        $response = $ta->tweet('MuPo: '.$translator->trans($report->getType())
                            .' №'.$report->getNumber().' peale '.$report->getStationBefore()
                            .', suunaga '.$report->getDestination().' @ '
                            .$report->getDatetime()->format('H:i d-m'));                             
                        $report->setTweeted($response->id_str);
                    }
                } else if ($report->getRating() <= -3) {
                    $report->setApproved(false);
                    if ($id = $report->getTweeted() && $id != 1) {
                        // UnTweet with id
                        $ta->getApi()->post('statuses/destroy', array('id' => $id));
                        $report->setTweeted(false);
                    }                    
                }
                
                $em->persist($report);

                $vote = new \Inori\MupoWatchBundle\Entity\ReportVote();
                $vote->setIp($ip);
                $vote->setReport($report);
                $vote->setVote($action);
                $em->persist($vote);

                $result = array('status' => 'success');
            } else {
                $result = array('status' => 'fail');
            }
            $result['result'] = $report->getRating();

            $em->flush();
        } else {
            $result = array('status' => 'fail');
        }

        return new Response(json_encode($result)); 
    }
}