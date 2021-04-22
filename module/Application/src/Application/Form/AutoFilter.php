<?php

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class AutoFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'      => 'rendszam',
            'required'  => true,
            'filters' => array(
                array('name' => 'StringTrim'),
                array('name' => 'StringToUpper'),
            ),
        ));
    }
}