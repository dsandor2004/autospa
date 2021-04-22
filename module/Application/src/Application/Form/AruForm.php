<?php
namespace Application\Form;
use Zend\Form\Form;

class AruForm extends Form
{
 
    public function __construct()
    {
        
        parent::__construct('aru_form');
                
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
                'label' => 'Áru neve',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'nev'
            )
        ));

        $this->add(array(
            'name' => 'vetelar',
            'type' => 'Text',
            'options' => array(
                'label' => 'Vétel Ár',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'vetelar'
            )
        ));
		
        $this->add(array(
            'name' => 'eladasiar',
            'type' => 'Text',
            'options' => array(
                'label' => 'Eladási Ár',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'eladasiar'
            )
        ));
		
        $this->add(array(
            'name' => 'darab',
            'type' => 'Text',
            'options' => array(
                'label' => 'Darab',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'darab'
            )
        ));
    }    
}