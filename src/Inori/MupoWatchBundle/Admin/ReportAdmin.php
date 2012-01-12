<?php

namespace Inori\MupoWatchBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Knp\Bundle\MenuBundle\Menu;
use Inori\TwitterAppBundle\Services\TwitterApp;
use Symfony\Component\Translation\Translator;

class ReportAdmin extends Admin
{
    public function __construct($code, $class, $baseControllerName, TwitterApp $ta, Translator $translator)
    {
        $this->ta = $ta;
        $this->translator = $translator;

        parent::__construct($code, $class, $baseControllerName);
    }
    
    protected $list = array(
        'datetime' => array('identifier' => true),
        'number' => array(),
        'type' => array(),
        'destination' => array(),
        'approved' => array(),
        'tweeted' => array(),
        'rating' => array(),
        'createdAt' => array(),
        '_action' => array('actions' => array('edit' => array(), 'delete' => array())),
    );

    protected $form = array(
        'datetime',
        'number',
        'type',
        'destination',
        'stationBefore',
        'info' => array('form_field_options' => array('required' => false)),
        'approved' => array('form_field_options' => array('required' => false)),
    );

    protected $baseRoutePattern = '/report';

    public function getBreadcrumbs($action)
    {
        $menu = new Menu();
        $item = $menu->addChild('Dashboard', $this->getRouter()->generate('sonata_admin_dashboard'));

        return $this->buildBreadcrumbs($action, $item);
    }
    
    public function preUpdate($object)
    {
        if ($object->getApproved() && !$object->getTweeted()) {
            $response = $this->ta->tweet('MuPo: '.$this->translator->trans($object->getType()).' №'.$object->getNumber().
                    ' peale '.$object->getStationBefore().', suunaga '.
                    $object->getDestination().' @ '.$object->getDatetime()->format('H:i d-m'));     
            $object->setTweeted($response->id_str);
        }
    }    
}
