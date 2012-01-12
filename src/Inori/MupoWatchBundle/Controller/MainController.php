<?php

namespace Inori\MupoWatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Inori\MupoWatchBundle\Entity\Report;
use Inori\MupoWatchBundle\Form\ReportType;

class MainController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        $response = new Response();
//        $date = new \DateTime();
//        $date->modify('+60 seconds');
//        $response->setExpires($date);

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->get('doctrine')->getEntityManager();

        $rq = $em->getRepository('InoriMupoWatchBundle:Report')->getLatestReportsQuery();

        $paginator = $this->get('knp_paginator');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $rq,
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        $pagination->setPageRange(5);

        return $this->render(
            'InoriMupoWatchBundle:Main:index.html.twig',
            array('paginator' => $pagination), $response
        );
    }

    /**
     * @Route("/add", name="add_report")
     */
    public function addReportAction()
    {
        $report = new Report();
        $form = $this->createForm(new ReportType(), $report);

        $request = $this->container->get('request');
        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getEntityManager();
                // Craaazy hacks here
                $transport = $this->get('doctrine')->getEntityManager()
                                ->getRepository('InoriMupoWatchBundle:Transport')
                                ->find($report->getNumber());
                if (!$transport) {
                    $this->get('session')->setFlash('notice', $this->get('translator')->trans('Vale info'));
                    return new RedirectResponse($this->generateUrl('add_report'));
                }
                $report->setNumber($transport->getNumber());
                $destination = $this->get('doctrine')->getEntityManager()
                                ->getRepository('InoriMupoWatchBundle:Destination')
                                ->find($report->getDestination());
                if (!$destination) {
                    $this->get('session')->setFlash('notice', $this->get('translator')->trans('Vale info'));
                    return new RedirectResponse($this->generateUrl('add_report'));
                }
                $report->setDestination($destination->getName());
                $station = $this->get('doctrine')->getEntityManager()
                                ->getRepository('InoriMupoWatchBundle:Station')
                                ->find($report->getStationBefore());
                if (!$station) {
                    $this->get('session')->setFlash('notice', $this->get('translator')->trans('Vale info'));
                    return new RedirectResponse($this->generateUrl('add_report'));
                }
                $report->setStationBefore($station->getName());
                // End of craazy hacks
                $em->persist($report);
                $em->flush();

                $this->get('session')->setFlash('notice', $this->get('translator')->trans('report.add.success'));
                return new RedirectResponse($this->generateUrl('add_report'));
            }
        }

        return $this->render('InoriMupoWatchBundle:Main:add_report.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
