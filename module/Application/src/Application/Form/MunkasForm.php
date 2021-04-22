<?php
namespace Application\Form;
use Zend\Form\Form;
use DateTime;
use DateInterval;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MunkasForm extends Form implements ObjectManagerAwareInterface
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

        $this->add(new \Application\Form\Element\HelySelect('hely'));

        $this->add(array(
            'name'          => 'munkastipus',
            'type'          => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager'    => $this->getObjectManager(),
                'target_class'      => 'Application\Entity\Munkastipus',
                'property'          => 'tipusnev',
                'display_empty_item' => true,
                'empty_item_label'   => '---Alkalmazott típusa---',                
            ),
            'attributes'    => array(
                'class'         =>  'form-control',
                'id'            =>  'munkastipus',
            ),
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Név',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'name'
            )
        ));
        
        $this->add(array(
            'name' => 'hiredate',
            'type' => 'Date',
            'options' => array(
                'label' => 'Alkalmazás dátuma',
            ),
            'attributes' => array(
                'min' => '2010-01-01',
                'max' => $now->add(new DateInterval("P1M"))->format('Y-m-d'),
                'class' => 'form-control',
                'id'    => 'hiredate'
            )
        ));
        
        $this->get('hiredate')->setFormat('Y-m-d');

        $this->add(array(
            'name' => 'birthdate',
            'type' => 'Date',
            'options' => array(
                'label' => 'Szülinap',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'birthdate'
            )
        ));
        
        $this->get('birthdate')->setFormat('Y-m-d');
        
        $checkbox = new \Zend\Form\Element\Checkbox('add_fizetes');
        $checkbox->setLabel('Az alapértelmezettől eltérő fizetés')
                ->setAttributes(array(
                    'id'    => 'add_fizetes',
                    'checked' => '',
                ));

        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue(1);
        $checkbox->setUncheckedValue(0);
        $this->add($checkbox);
        
        $this->add(array(
            'name' => 'fizetes',
            'type' => 'Text',
            'options' => array(
                'label' => 'Fizetés',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'fizetes'
            )
        ));
        
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