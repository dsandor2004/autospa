<?php
namespace Application\Form;

use Zend\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserForm extends Form implements ObjectManagerAwareInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);
        
        parent::__construct('user_form');
    
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
                'attributes' => array(
                    'id'    => 'id'
                )
         ));

        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Felhasználónév',
            ),
            'attributes' => array(
                'class'         => 'form-control',
                'id'            => 'username',
                'autocomplete'  => 'off'
            )
        ));
        
        $this->add(array(
            'name'          =>  'password',
            'type'          => 'Password',
            'options'       => array(
                'label' => 'Jelszó'
            ),
            'attributes'    => array(
                'class'         =>  'form-control',
                'id'            =>  'password',
                'autocomplete'  => 'off'
            )
        ));
        
        $this->add(array(
            'name'          => 'roles',
            'type'          => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager'    => $this->getObjectManager(),
                'target_class'      => 'SamUser\Entity\Role',
                'property'          => 'roleId',
                'display_empty_item' => true,
                'empty_item_label'   => '---Jogosultság---',                
            ),
            'attributes'    => array(
                'multiple' => 'multiple',
                'class'         =>  'form-control',
                'id'            =>  'roles',
            ),
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