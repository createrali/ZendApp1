<?php  
namespace Employee\Form; 
use Zend\Form\Form;  

class EmployeeSearchForm extends Form { 
   public function __construct($name = null) { 
      
      parent::__construct('employee');
      $this->add(array( 
         'name' => 'emp_name', 
         'type' => 'Text', 
         'attributes' => array(
            'class' => 'px-2'
         ), 
      )); 
      $this->add(array( 
         'name' => 'submit', 
         'type' => 'Submit', 
         'attributes' => array(
            'value' => 'Search', 
            'id' => 'submitbutton',
            'class' => 'px-2'
         ), 
      )); 
   } 
} 