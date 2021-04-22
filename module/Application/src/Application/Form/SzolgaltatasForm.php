<?php
namespace Application\Form;
use Zend\Form\Form;

class SzolgaltatasForm extends Form
{
 
    public function __construct($name = null)
    {
        parent::__construct('szolgaltatas_form');
                
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
                'label' => 'Szolgáltatás neve',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'nev'
            )
        ));
        
        $this->add(array(
            'name' => 'alapar',
            'type' => 'Text',
            'options' => array(
                'label' => 'Alap ár',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'alapar'
            )
        ));
        
        $this->add(array(
            'name' => 'cegesar',
            'type' => 'Text',
            'options' => array(
                'label' => 'Céges ár',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'cegesar'
            )
        ));
        
    } 
}