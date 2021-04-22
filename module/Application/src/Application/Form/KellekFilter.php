<?php

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class KellekFilter extends InputFilter
{
    public function __construct()
    {        
        $this->add(array(
            'name'      => 'nev',
            'required'  => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
        ));        
    }
}