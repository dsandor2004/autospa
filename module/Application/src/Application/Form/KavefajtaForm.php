<?php
namespace Application\Form;
use Zend\Form\Form;

class KavefajtaForm extends Form
{
 
    public function __construct($name = null)
    {
        parent::__construct('kavefajta_form');
                
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
                'label' => 'Kávéfajta neve',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'nev'
            )
        ));
		
        $this->add(array(
            'name' => 'mennyiseg',
            'type' => 'Text',
            'options' => array(
                'label' => 'Kávémennyiség (gram)',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'mennyiseg'
            )
        ));
		
        $this->add(array(
            'name' => 'tejmennyiseg',
            'type' => 'Text',
            'options' => array(
                'label' => 'Tejmennyiség (ml)',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'tejmennyiseg'
            )
        ));		
		
        $this->add(array(
            'name' => 'ar',
            'type' => 'Text',
            'options' => array(
                'label' => 'Ár',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'ar'
            )
        ));
    }
}