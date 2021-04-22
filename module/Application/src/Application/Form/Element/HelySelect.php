<?php
namespace Application\Form\Element;
use Zend\Form\Element;

class HelySelect extends Element\Select
{    
    public function getInputSpecification()
    {
        $this->setValueOptions(
                array(
                    \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID => \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO,
                    \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID    => \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA,
                )
        );
        
        $this->setAttributes(array(
            'class' => 'form-control',
            'id'    => $this->getName(),
        ));
        
        $this->setLabel('Helyszín');
        
        $this->setEmptyOption('--Válassz helyszínt--');
        
        return array('name' => $this->getName());
    }    
}