<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;

/**
 * This form is used to collect fiche informations
 */
class AttributAddForm extends Form
{
    public function __construct()
    {
        // Define form name
        parent::__construct('attributadd-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        $this->addElements();
        $this->addInputFilter(); 
        
    }
    

    protected function addElements() 
    {
        // Add "nom" field
        $this->add([            
            'type'  => 'text',
            'name' => 'nom',
            'options' => [
                'label' => 'Nom',
            ],
        ]);
        /*
        // Add "valeur" field
        $this->add([            
            'type'  => 'text',
            'name' => 'valeur',
            'options' => [
                'label' => 'Valeur',
            ],
        ]);
        */
        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Valider',
                'id' => 'submit',
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() 
    {
        // Create main input filter
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        // Add input for "nom" field
        $inputFilter->add([
                'name'     => 'nom',
                'required' => true,
                'filters'  => [                    
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                ],
            ]);
    }
}

