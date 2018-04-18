<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;

/**
 * This form is used to collect user's login, password and 'Remember Me' flag.
 */
class ProductEditForm extends Form
{
    public function __construct(/*Product $elem*/)
    {
        // Define form name
        parent::__construct('productedit-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        $this->addElements();
        $this->addInputFilter(); 
        
    }
    

    protected function addElements() 
    {
        $this->add([            
            'type'  => 'text',
            'name' => 'nom',
            'options' => [
                'label' => 'Nom',
            ],
        ]);
        
        $this->add([            
            'type'  => 'text',
            'name' => 'prix',
            'options' => [
                'label' => 'Prix',
            ],
        ]);
        
        $this->add([            
            'type'  => 'text',
            'name' => 'description',
            'options' => [
                'label' => 'Description',
            ],
        ]);
        
        $this->add([            
            'type'  => 'hidden',
            'name' => 'image',
        ]);
        
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
                
        // Add input for "prix" field
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
                
            // Add input for "prix" field
            $inputFilter->add([
                    'name'     => 'prix',
                    'required' => true,
                    'filters'  => [
                        ['name' => 'StringTrim'],                    
                    ],                
                    'validators' => [
                        [
                            'name' => 'Digits'
                        ],
                    ],
                ]);
    }
}

