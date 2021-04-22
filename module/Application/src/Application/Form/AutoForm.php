<?php
namespace Application\Form;
use Zend\Form\Form;

class AutoForm extends Form
{
 
    public function __construct($name = null)
    {         
        parent::__construct('auto_form');
                
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'id'    => 'id'
            )
         ));

        $this->add(array(
            'name' => 'rendszam',
            'type' => 'Text',
            'options' => array(
                'label' => 'Rendszám',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'rendszam'
            )
        ));        
    } 
}