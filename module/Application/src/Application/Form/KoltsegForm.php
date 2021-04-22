<?php
namespace Application\Form;
use Application\Entity\Koltseg;
use Zend\Form\Form;
use DateTime;
use DateInterval;

class KoltsegForm extends Form
{
 
    public function __construct()
    {
        $now = new DateTime();
        
        parent::__construct('koltseg_form');
                
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'id'    => 'id'
            )
         ));
                
        $this->add(array(
            'name'  => 'tipus',
            'type'  => 'Select',
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'tipus'
            ),
            'options' => array(
                'label' => 'Típus',
                'empty_option' => '--Válassz típust--',
                'value_options' => array(
                    Koltseg::TIPUS_MOSODA_FIKO => Koltseg::TIPUS_MOSODA_FIKO_STRING,
                    Koltseg::TIPUS_MOSODA_KORONKA => Koltseg::TIPUS_MOSODA_KORONKA_STRING,
                    Koltseg::TIPUS_VULKANIZALO_FIKO => Koltseg::TIPUS_VULKANIZALO_FIKO_STRING,
                    Koltseg::TIPUS_VULKANIZALO_KORONKA => Koltseg::TIPUS_VULKANIZALO_KORONKA_STRING,
                    Koltseg::TIPUS_SZERVIZ_KORONKA => Koltseg::TIPUS_SZERVIZ_KORONKA_STRING,
                )
            )
        ));
        
        $this->add(array(
            'name' => 'koltsegnev',
            'type' => 'Text',
            'options' => array(
                'label' => 'Költség neve',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'koltsegnev'
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