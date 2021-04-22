<?php
namespace Application\Form;
use Zend\Form\Form;

class KellekForm extends Form
{
 
    public function __construct($name = null)
    {
        parent::__construct('kellek_form');
                
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'id'    => 'id'
            )
         ));
        
        $this->add(array(
            'name' => 'nev',
            'type' => 'Text',
            'options' => array(
                'label' => 'Kellék neve',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'nev'
            )
        ));        
    } 
}