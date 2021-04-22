<?php

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class AktivitasFilter extends InputFilter
{
    public function __construct()
    {        
        $this->add(array(
            'name'      => 'ceg',
            'required'  => false,
        ));        
        
        $this->add(array(
            'name'      => 'kellekek',
            'required'  => false,
        ));        

        $this->add(array(
            'name'      => 'munkasok',
            'required'  => false,
        ));        

        $this->add(array(
            'name'      => 'ar',
            'required'  => true,
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