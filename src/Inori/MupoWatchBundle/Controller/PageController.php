<?php

namespace Inori\MupoWatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Inori\MupoWatchBundle\Entity\Report;

/**
 * @Route("/info")
 */
class PageController extends Controller
{
    /**
     * @Route("/", name="index_info")
     */
    public function indexAction()
    {
        return $this->render('InoriMupoWatchBundle:Page:index.html.twig');
    }
        
    /**
     * @Route("/how", name="how_info")
     */
    public function howAction()
    {
        return $this->render('InoriMupoWatchBundle:Page:how.html.twig');
    }
    
    /**
     * @Route("/stats", name="stats_info")
     */
    public function statsAction()
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->get('doctrine')->getEntityManager();

        $reports = $em->getRepository('InoriMupoWatchBundle:Report')->getReportStats();
        return $this->render('InoriMupoWatchBundle:Page:stats.html.twig', array('reports' => $reports));
    }    
}