<?php

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class UgyintezofizetesFilter extends InputFilter
{
    public function __construct()
    {        
        $this->add(array(
            'name'      => 'osszeg',
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
        
        $this->add(array(
            'name'      => 'datum',
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
        
    }
}