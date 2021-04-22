<?php

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class TeendoFilter extends InputFilter
{
    public function __construct()
    {        
        $this->add(array(
            'name'      => 'description',
            'required'  => true,
        ));        
    }
}