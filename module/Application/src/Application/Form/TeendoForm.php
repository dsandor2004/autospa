<?php
namespace Application\Form;
use Zend\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TeendoForm extends Form implements ObjectManagerAwareInterface
{
 
    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);
        
        parent::__construct('teendo_form');
                
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'id'    => 'id'
            )
         ));
                
        
        $this->add(array(
            'name'          => 'user',
            'type'          => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label'             => 'Kihez rendeled',
                'object_manager'    => $this->getObjectManager(),
                'target_class'      => 'SamUser\Entity\User',
                'property'          => 'username',
                'display_empty_item' => true,
                'empty_item_label'   => '--Válassz felhasználót--',
            ),
            'attributes'    => array(
                'class'         =>  'form-control',
                'id'            =>  'user',
            ),
        ));
                
        $this->add(array(
            'name' => 'description',
            'type' => 'Text',
            'options' => array(
                'label' => 'Teendő részletei',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'description'
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