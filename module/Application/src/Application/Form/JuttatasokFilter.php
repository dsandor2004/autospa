<?php

namespace Application\Form;
use Zend\InputFilter\InputFilter;

class JuttatasokFilter extends InputFilter
{
    public function __construct()
    {        
        $this->add(array(
            'name'      => 'ejjelipenz',
            'required'  => true,
            'validators' => array(
                array(
                    'name'  => 'Regex',
                    'options' => array(
                        'pattern' => '/^\d+(\.\d+)?$/',
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
            'name'      => 'reklampenz',
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
            'name'      => 'habpenz',
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
            'name'      => 'baroscsaj',
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