<?php
namespace Application\Form;
use Zend\Form\Form;

class JuttatasokForm extends Form
{
 
    public function __construct($name = null)
    {
        parent::__construct('juttatasok_form');
        
        $this->add(array(
            'name' => 'ejjelipenz',
            'type' => 'Text',
            'options' => array(
                'label' => 'Éjjelipénz/nap',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'ejjelipenz'
            )
        ));
        
        $this->add(array(
            'name' => 'habpenz',
            'type' => 'Text',
            'options' => array(
                'label' => 'Habpénz/hónap',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'habpenz'
            )
        ));

        $this->add(array(
            'name' => 'reklampenz',
            'type' => 'Text',
            'options' => array(
                'label' => 'Reklámpénz/hónap',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'reklampenz'
            )
        ));

        $this->add(array(
            'name' => 'baroscsaj',
            'type' => 'Text',
            'options' => array(
                'label' => 'Bároscsaj jutalék/autó (csak nem fisás)',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id'    => 'baroscsaj'
            )
        ));
        
    } 
}