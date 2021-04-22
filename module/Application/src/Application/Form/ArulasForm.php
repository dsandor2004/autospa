<?php
namespace Application\Form;
use Zend\Form\Form;
use Application\Entity\Aruforgas;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArulasForm extends Form implements ObjectManagerAwareInterface
{
 
    public function __construct(ObjectManager $objectManager)
    {         
        $this->setObjectManager($objectManager);
        
        parent::__construct('arulas_form');
                
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'id'    => 'id'
            )
         ));
        
        $this->add(array(
            'name'          => 'aru',
            'type'          => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Áru',
                'object_manager'    => $this->getObjectManager(),
                'target_class'      => 'Application\Entity\Aru',
                'property'          => 'nev',
                'is_method'         => true,
                'find_method'       => array(
                    'name'          => 'findBy',
                    'params'        => array(
                        'criteria'  => array ('status' => \Application\Entity\Aru::STATUS_ACTIVE),
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label'   => '---Áru---',                
            ),
            'attributes'    => array(
                'class'         =>  'form-control',
                'id'            =>  'aru',
            ),
        ));

        $this->add(array(
            'name'			=> 'kavefajta',
            'type'			=> 'DoctrineModule\Form\Element\ObjectSelect',
            'options'		=> array(
                'label'				=> 'Kávéfajta',
                'object_manager'	=> $this->getObjectManager(),
                'target_class'      => 'Application\Entity\Kavefajta',
                'property'          => 'nev',
                'is_method'         => true,
                'find_method'       => array(
                    'name'		=> 'findBy',
                    'params'	=> array(
						'criteria'  => array (),
						'orderBy'	=> array('nev' => 'ASC'),
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label'   => '---Kávéfajta---',                
            ),
            'attributes'	=> array(
                'class'	=>  'form-control',
                'id'    =>  'kavefajta',
            ),
        ));
		
        $this->add(array(
            'name' => 'cukor',
            'type' => 'Text',
            'options' => array(
                'label' => 'Cukor',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'cukor'
            )
        ));
		
        $checkbox = new \Zend\Form\Element\Checkbox('pohar');
        $checkbox->setLabel('Pohár')
                ->setAttributes(array(
                    'id'    => 'pohar',
                    'checked' => '',
                ));

        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue(1);
        $checkbox->setUncheckedValue(0);
        $this->add($checkbox);

        $checkbox = new \Zend\Form\Element\Checkbox('poharfedo');
        $checkbox->setLabel('Pohárfedő')
                ->setAttributes(array(
                    'id'    => 'poharfedo',
                    'checked' => '',
                ));

        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue(1);
        $checkbox->setUncheckedValue(0);
        $this->add($checkbox);
		
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
		
        $this->add(array(
            'name'  => 'tipus',
            'type'  => 'Select',
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'tipus'
            ),
            'options' => array(
                'label' => 'Tipus',
                'value_options' => array(
                    Aruforgas::TIPUS_NORMAL		=> Aruforgas::TIPUS_NORMAL_STRING,
                    Aruforgas::TIPUS_MUNKASNAK  => Aruforgas::TIPUS_MUNKASNAK_STRING,
                    Aruforgas::TIPUS_INGYEN		=> Aruforgas::TIPUS_INGYEN_STRING,
                )
            )
        ));
		
        $this->add(array(
            'name'          => 'munkas',
            'type'          => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Munkás',
                'object_manager'    => $this->getObjectManager(),
                'target_class'      => 'Application\Entity\Munkas',
                'property'          => 'name',
                'is_method'         => true,
                'find_method'       => array(
                    'name'          => 'findBy',
                    'params'        => array(
                        'criteria'  => array ('status' => \Application\Entity\Munkas::STATUS_ACTIVE),
						'orderBy' => array('name' => 'ASC'),
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label'   => '---Munkás---',                
            ),
            'attributes'    => array(
                'class'         =>  'form-control',
                'id'            =>  'munkas',
            ),
        ));
		
        $this->add(array(
            'name' => 'megjegyzes',
            'type' => 'Text',
            'options' => array(
                'label' => 'Megjegyzés',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'megjegyzes'
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