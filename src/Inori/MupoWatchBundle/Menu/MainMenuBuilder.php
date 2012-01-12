<?php

namespace Inori\MupoWatchBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class MainMenuBuilder extends ContainerAware
{
    /**
     * The menu along the left side of the account section
     *
     * @param \Knp\Menu\FactoryInterface $factory

     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory)
    {
        $translator = $this->container->get('translator');

        $builder = $factory->createItem('main');
        $builder->setAttributes(array('id' => 'nav_menu', 'class' => 'menu'));
        $builder->setCurrentUri($this->container->get('request')->getRequestUri());

        $builder->addChild($translator->trans('search'), array('route' => 'index'));
        $builder->addChild($translator->trans('add'), array('route' => 'add_report'));
        $builder->addChild($translator->trans('info'), array('route' => 'index_info'));
        $builder->addChild($translator->trans('stats'), array('route' => 'stats_info'));

        return $builder;
    }
}