<?php
namespace Application\Form;
use Application\Entity\Aktivitas;
use Zend\Form\Form;
use DateTime;
use DateInterval;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AktivitasForm extends Form implements ObjectManagerAwareInterface
{
 
    public function __construct(ObjectManager $objectManager)
    {         
        $this->setObjectManager($objectManager);
        
        $now = new DateTime();
        
        parent::__construct('aktivitas_form');
                
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
            'attributes' => array(
                'id'    => 'id'
            )
         ));

        $this->add(new \Application\Form\Element\HelySelect('hely'));
        
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
                    Aktivitas::TIPUS_NORMAL => Aktivitas::TIPUS_NORMAL_STRING,
                    Aktivitas::TIPUS_CEG    => Aktivitas::TIPUS_CEG_STRING,
                    Aktivitas::TIPUS_FIDEL  => Aktivitas::TIPUS_FIDEL_STRING,
                    Aktivitas::TIPUS_INGYEN => Aktivitas::TIPUS_INGYEN_STRING,
                    Aktivitas::TIPUS_FIKO   => Aktivitas::TIPUS_FIKO_STRING,
                )
            )
        ));
        
        $this->add(array(
            'name'          => 'ceg',
            'type'          => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Cég',
                'object_manager'    => $this->getObjectManager(),
                'target_class'      => 'Application\Entity\Ceg',
                'property'          => 'cegname',
                'is_method'         => true,
                'find_method'       => array(
                    'name'          => 'findBy',
                    'params'        => array(
                        'criteria'  => array ('status' => \Application\Entity\Ceg::STATUS_ACTIVE),
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label'   => '---Cég---',                
            ),
            'attributes'    => array(
                'class'         =>  'form-control',
                'id'            =>  'ceg',
            ),
        ));
        
        $repo = $this->getObjectManager()->getRepository('\Application\Entity\Szolgaltatas');
        $szolgaltatasok = $repo->findBy(array(), array('nev' => 'asc'));
        
        $optArray = array('' => '--Szolgáltatás--');
        foreach ($szolgaltatasok as $opt) {
            $optArray[$opt->getId()] = $opt->getNev();
        }
        
        $optArray['0'] = '-Egyéb-';
        
        $select = new \Zend\Form\Element\Select('szolgaltatas');        
        $select->setValueOptions($optArray)
                ->setLabel('Szolgáltatás')
                ->setAttributes(array(
                    'class'         =>  'form-control',
                    'id'            =>  'szolgaltatas',
                ));
        
        $this->add($select);
        
        
        $this->add(array(
            'name' => 'nev',
            'type' => 'Text',
            'options' => array(
                'label' => 'Szolgáltatás neve',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'nev'
            )
        ));
        
        $checkbox = new \Zend\Form\Element\Checkbox('viasz');
        $checkbox->setLabel('Viasz')
                ->setAttributes(array(
                    'id'    => 'viasz',
                    'checked' => '',
                ));

        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue(1);
        $checkbox->setUncheckedValue(0);
        $this->add($checkbox);

        $checkbox = new \Zend\Form\Element\Checkbox('sma');
        $checkbox->setLabel('Spălat motor cu aburi')
                ->setAttributes(array(
                    'id'    => 'sma',
                    'checked' => '',
                ));

        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue(1);
        $checkbox->setUncheckedValue(0);
        $this->add($checkbox);
		
        $checkbox = new \Zend\Form\Element\Checkbox('smac');
        $checkbox->setLabel('Spălat motor cu aburi chimic')
                ->setAttributes(array(
                    'id'    => 'smac',
                    'checked' => '',
                ));

        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue(1);
        $checkbox->setUncheckedValue(0);
        $this->add($checkbox);

        $checkbox = new \Zend\Form\Element\Checkbox('ss');
        $checkbox->setLabel('Spălat șasiu')
                ->setAttributes(array(
                    'id'    => 'ss',
                    'checked' => '',
                ));

        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue(1);
        $checkbox->setUncheckedValue(0);
        $this->add($checkbox);
		
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

        $checkbox = new \Zend\Form\Element\Checkbox('status');
        $checkbox->setLabel('Kifizetve')
                ->setAttributes(array(
                    'id'    => 'status',
                    'checked' => '',
                ));

        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue(1);
        $checkbox->setUncheckedValue(0);
        $this->add($checkbox);
        
        $this->add(array(
            'name' => 'rendszam',
            'type' => 'Text',
            'options' => array(
                'label' => 'Rendszám',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'rendszam'
            )
        ));
        
        $this->add(array(
            'name' => 'idopont',
            'type' => 'Date',
            'options' => array(
                'label' => 'Dátum',
            ),
            'attributes' => array(
                'min' => '2010-01-01',
                'max' => $now->add(new DateInterval("P1M"))->format('Y-m-d'),
                'class' => 'form-control',
                'id'    => 'idopont'
            )
        ));
        
        $this->get('idopont')->setFormat('Y-m-d');
        $now = new DateTime();
        $this->get('idopont')->setValue($now->format('Y-m-d'));
        
        $checkbox = new \Zend\Form\Element\Checkbox('kellek_megkezdve');
        $checkbox->setLabel('Kellék megkezdve')
                ->setAttributes(array(
                    'id'    => 'kellek_megkezdve',
                    'checked' => '',
                ));

        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue(1);
        $checkbox->setUncheckedValue(0);
        $this->add($checkbox);

        
        $this->add(array(
            'name'          => 'kellekek',
            'type'          => '\DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'options' => array(
//                'label' => 'Cég',
                'object_manager'    => $this->getObjectManager(),
                'target_class'      => 'Application\Entity\Kellek',
                'property'          => 'nev',
            ),
            'attributes'    => array(
                'class'         =>  'form-control',
                'id'            =>  'kellekek',
            ),
        ));
        
        $this->add(array(
            'name'          => 'munkasok',
            'type'          => '\DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'options' => array(
                'label' => 'Alkalmazottak',
                'object_manager'    => $this->getObjectManager(),
                'target_class'      => 'Application\Entity\Munkas',
                'property'          => 'name',
                'is_method'         => true,
                'find_method'       => array(
                    'name'          => 'findBy',
                    'params'        => array(
                        'criteria'  => array ('status' => \Application\Entity\Munkas::STATUS_ACTIVE),
                    ),
                ),
                
            ),
            'attributes'    => array(
                'class'         =>  'form-control',
                'id'            =>  'munkasok',
            ),
        ));
        
        $this->add(array(
            'name'  => 'vulk_20',
            'type'  => 'Select',
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'vulk_20'
            ),
            'options' => array(
                'label' => 'Kor. Vulk. ' . \Application\Entity\Reszesedes::VULK_RESZ_SZAZALEK . '%',
                'value_options' => array(
                    '0' => 'Nem',
                    '1' => 'Igen'
                )
            )
        ));
        
        $qb = $this->getObjectManager()->getRepository('\Application\Entity\Munkas')->createQueryBuilder('m')
                ->select('m')
                ->where("m.munkastipus <> " . \Application\Entity\Munkastipus::BAROS_TIPUS_ID . " AND m.status = " . \Application\Entity\Munkas::STATUS_ACTIVE)
                ->orderBy('m.name', 'ASC');
                
        $munkasok = $qb->getQuery()->getResult();
        
        $optArray = array('0' => '--Válassz--');
        foreach ($munkasok as $opt) {
            $optArray[$opt->getId()] = $opt->getName();
        }
                
        foreach ($this->get('kellekek')->getValueOptions() as $key => $value) {
            
            $select = new \Zend\Form\Element\Select('kellek_munkas_' . $value['value']);
            $select->setValueOptions($optArray)
                    ->setAttributes(array(
                        'class'         =>  'form-control',
                        'id'            =>  'kellek_munkas_' . $value['value'],
                    ));

            $this->add($select);
        }
        
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