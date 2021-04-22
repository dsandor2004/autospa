<?php

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class UserFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'      => 'username',
            'required'  => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name'      => 'password',
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                    ),
                ),
            ),
        ));
        
//        $this->add(array(
//            'name'      => 'roles',
//            'required'  => true,
//        ));
    }
}