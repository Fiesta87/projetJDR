<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect user's credit card informations
 */
class PaymentForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('payment-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
                
        $this->addElements();      
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
        // Add "username" field
        $this->add([            
            'type'  => 'text',
            'name' => 'username',
            'options' => [
                'label' => 'Nom du propriétaire de la carte',
            ],
        ]);
        
        // Add "card" field
        $this->add([            
            'type'  => 'text',
            'name' => 'card',
            'options' => [
                'label' => 'Numéro de carte banquaire',
            ],
        ]);
        
        // Add "cvv" field
        $this->add([            
            'type'  => 'text',
            'name' => 'cvv',
            'options' => [
                'label' => 'Code de sécurité (CVV)',
            ],
        ]);
        
        // Add "expiration" field
        $this->add([            
            'type'  => 'text',
            'name' => 'expiration',
            'options' => [
                'label' => 'Date d\'expiration',
            ],
        ]);
        
        // Add the CSRF field
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                'timeout' => 600
                ]
            ],
        ]);
        
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
}
