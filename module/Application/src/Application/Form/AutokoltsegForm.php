<?php
namespace Application\Form;
use Application\Entity\Autokoltseg;
use Zend\Form\Form;
use DateTime;
use DateInterval;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AutokoltsegForm extends Form implements ObjectManagerAwareInterface
{
 
    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);
        
        $now = new DateTime();
        
        parent::__construct('autokoltseg_form');
                
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'id'    => 'id'
            )
         ));

        $this->add(array(
            'name'          => 'auto',
            'type'          => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager'    => $this->getObjectManager(),
                'target_class'      => 'Application\Entity\Auto',
                'property'          => 'rendszam',
                'is_method'         => true,
                'find_method'       => array(
                    'name'          => 'findBy',
                    'params'        => array(
                        'criteria'  => array ('status' => \Application\Entity\Auto::STATUS_ACTIVE),
                    ),
                ),
                
                'display_empty_item' => true,
                'empty_item_label'   => '---Autó---',                
            ),
            'attributes'    => array(
                'class'         =>  'form-control',
                'id'            =>  'auto',
            ),
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
                'value_options' => array(
                    Autokoltseg::UZEMANYAG_TIPUS => Autokoltseg::UZEMANYAG_STRING,
                    Autokoltseg::EGYEB_TIPUS => 'Egyéb'
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
    
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
 
        return $this;
    }
 
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}