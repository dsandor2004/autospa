<?php

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class MunkasFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'      => 'name',
            'required'  => true,
            'filters' => array(
                array('name' => 'StringTrim'),
                array('name' => 'StringToUpper'),
            ),
        ));
        
        $this->add(array(
            'name'      => 'hiredate',
            'required'  => true,
            'validators' => array(
                array(
                    'name'      => 'Date',
                    'options' => array(
                        'format' => 'Y-m-d'
                    )
                ),
                
                array(
                    'name'  => 'Regex',
                    'options' => array(
                        'pattern' => '/(01|16)$/',
                        'messages' => array(
                            "regexInvalid" => "Az alkalmazási dátum napja 1 vagy 16 kell legyen",
                            "regexNotMatch" => "Az alkalmazási dátum napja 1 vagy 16 kell legyen",
                            "regexErrorous" => "Az alkalmazási dátum napja 1 vagy 16 kell legyen",
                        )
                    ),
                )
                
            ),
        ));

        $this->add(array(
            'name'      => 'birthdate',
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
            'name'      => 'fizetes',
            'required'  => false,
            'validators' => array(
                array(
                    'name'  => 'Regex',
                    'options' => array(
                        'pattern' => '/^\d+(\.\d{1,2})?$/',
                        'messages' => array(
                            "regexInvalid" => "A megadott érték hibás",
                            "regexNotMatch" => "A megadott érték hibás",
                            "regexErrorous" => "A megadott érték hibás",
                        )
                    ),
                ),
            ),
        ));
        
    }
}