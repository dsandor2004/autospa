<?php
namespace Application\Form;
use DateTime;
use DateInterval;
use Zend\Form\Form;

class CegForm extends Form
{
 
    public function __construct($name = null)
    {         
        parent::__construct('ceg_form');
                
        $now = new DateTime();
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'id'    => 'id'
            )
         ));

        $this->add(array(
            'name' => 'cegname',
            'type' => 'Text',
            'options' => array(
                'label' => 'Cég neve',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'cegname'
            )
        ));
        
        $this->add(array(
            'name' => 'szerzodesdatum',
            'type' => 'Date',
            'options' => array(
                'label' => 'Szerződés dátuma',
            ),
            'attributes' => array(
                'min' => '2010-01-01',
                'max' => $now->add(new DateInterval("P1M"))->format('Y-m-d'),
                'class' => 'form-control',
                'id'    => 'szerzodesdatum'
            )
        ));
        
        $this->get('szerzodesdatum')->setFormat('Y-m-d');
        
        $checkbox = new \Zend\Form\Element\Checkbox('automatikushosszabbitas');
        $checkbox->setLabel('A szerződés automatikus meghosszabítása lejáratkor')
                ->setAttributes(array(
                    'id'    => 'automatikushosszabbitas',
                    'checked' => '',
                ));

        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue(1);
        $checkbox->setUncheckedValue(0);
        $this->add($checkbox);

        $this->add(array(
            'name' => 'idotartam',
            'type' => 'Text',
            'options' => array(
                'label' => 'Szerződési időtartam (hónap)',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'idotartam'
            )
        ));
        
        $fizetesihatarido = new \Zend\Form\Element\Select('fizetesihatarido');
        $fizetesihatarido->setLabel('Fizetési határidő')
                ->setAttributes(array(
                    'class' => 'form-control',
                    'id'    => 'fizetesihatarido',
                ))
                ->setValueOptions(array(
                    '0'     => 'Azonnal',
                    '7'     => '7 nap',
                    '15'    => '15 nap',
                    '30'    => '30 nap',
                    '45'    => '45 nap',
                    '60'    => '60 nap',
                    '-1'    => 'Minden hónap végén',
                ));

        $checkbox2 = new \Zend\Form\Element\Checkbox('szazalekmunkas');
        $checkbox2->setLabel('A munkások kapnak százalékot erre a cégre')
                ->setAttributes(array(
                    'id'    => 'szazalekmunkas',
                    'checked' => '',
                ));

        $checkbox2->setUseHiddenElement(true);
        $checkbox2->setCheckedValue(1);
        $checkbox2->setUncheckedValue(0);
        $this->add($checkbox2);
        
        $this->add($fizetesihatarido);
    } 
}