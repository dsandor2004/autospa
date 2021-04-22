<?php

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class CegFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'      => 'cegname',
            'required'  => true,
            'filters' => array(
                array('name' => 'StringTrim'),
                array('name' => 'StringToUpper'),
            ),
        ));
        
        $this->add(array(
            'name'      => 'szerzodesdatum',
            'required'  => true,
            'validators' => array(
                array(
                    'name'      => 'Date',
                    'options' => array(
                        'format' => 'Y-m-d'
                    )
                ),
            ),
        ));
        
        $this->add(array(
            'name'      => 'idotartam',
            'required'  => true,
            'validators'    => array(
                array(
                    'name'  => 'Digits',
                ),
                array(
                    'name'  => 'Between',
                    'options' => array(
                        'min'   => 1,
                        'max'   => 24,
                    ),
                ),
            ),
        ));
        
    }
}