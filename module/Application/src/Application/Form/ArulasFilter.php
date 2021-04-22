<?php

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class ArulasFilter extends InputFilter
{
    public function __construct()
    {        
        $this->add(array(
            'name'      => 'aru',
            'required'  => true,
        ));
		
        $this->add(array(
            'name'		=> 'kavefajta',
            'required'  => false,
            'validators' => array(
                array(
                    'name'  => 'Digits',
                ),
            ),
        ));
		
        $this->add(array(
            'name'		=> 'darab',
            'required'  => true,
            'validators' => array(
                array(
                    'name'  => 'Digits',
                ),
            ),
        ));
		
        $this->add(array(
            'name'		=> 'munkas',
            'required'  => false,
            'validators' => array(
                array(
                    'name'  => 'Digits',
                ),
            ),
        ));
		
    }
}