<?php

namespace Inori\MupoWatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ReportType extends AbstractType
{
    public function getName()
    {
        return 'report';
    }    
    
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array('required' => true, 'label' => 'Tüüp', 'choices' => $this->getTypes()))        
            ->add('number', 'choice', array('required' => true, 'label' => 'Number', 'choices' => array()))
            ->add('destination', 'choice', array('required' => true, 'label' => 'Sihtpunkt', 'choices' => array()))
            ->add('stationBefore', 'choice', array('required' => true, 'label' => 'Peatus enne', 'choices' => array()))
            //->add('info', 'textarea', array('required' => false, 'label' => 'Info'))
            ->add('datetime', 'time', array('required' => true, 'label' => 'Aeg',
                'hours' => $this->getHours(), 'minutes' => $this->getMinutes()))
        ;
    }
    
    protected function getTypes()
    {
        return array(false => 'Tüüp', 'bus' => 'bus', 'troll' => 'troll', 'tram' => 'tram');
    }
    
    protected function getHours()
    {
        if (date('G') > 6 && date('G') < 24) {
            return range(6, date('G'));
        } else {
            return range(6,23);
        }
    }
    
    protected function getMinutes()
    {
        return range(0, 55, 5);    
    }
}