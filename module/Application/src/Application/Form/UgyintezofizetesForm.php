<?php
namespace Application\Form;
use Zend\Form\Form;
use DateTime;
use DateInterval;

class UgyintezofizetesForm extends Form
{
 
    public function __construct()
    {
        $now = new DateTime();
        
        parent::__construct('Ugyintezofizetes_form');
                
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'id'    => 'id'
            )
         ));
        
        $this->add(array(
            'name' => 'osszeg',
            'type' => 'Text',
            'options' => array(
                'label' => 'Összeg',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'osszeg'
            )
        ));
        
        $this->add(array(
            'name' => 'datum',
            'type' => 'Date',
            'options' => array(
                'label' => 'Dátum',
            ),
            'attributes' => array(
                'min' => '2010-01-01',
                'max' => $now->add(new DateInterval("P1M"))->format('Y-m-d'),
                'class' => 'form-control',
                'id'    => 'datum'
            )
        ));
        
        $this->get('datum')->setFormat('Y-m-d');       
    }    
}