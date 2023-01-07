<?php  
namespace Employee\Form; 
use Zend\Form\Form;  

class EmployeeForm extends Form { 
   public function __construct($name = null) { 
      
      parent::__construct('employee');  
      $this->add(array( 
         'name' => 'emp_id', 
         'type' => 'Hidden', 
      )); 
      $this->add(array( 
         'name' => 'emp_name', 
         'type' => 'Text',
         'required' => true, 
         'options' => array( 
            'label' => 'Name:'
         ), 
      )); 
      $this->add(array( 
         'name' => 'emp_address', 
         'type' => 'Textarea', 
         'required' => true, 
         'options' => array( 
            'label' => 'Address:', 
         ), 
      )); 
      $this->add(array( 
         'name' => 'emp_email', 
         'type' => 'Email', 
         'required' => true, 
         'options' => array( 
            'label' => 'Email:', 
         ), 
      )); 
      $this->add(array( 
         'name' => 'emp_phone', 
         'type' => 'Text', 
         'required' => true, 
         'options' => array( 
            'label' => 'Phone No:', 
         ), 
      )); 
      $this->add(array( 
         'name' => 'emp_dob', 
         'type' => 'Date', 
         'required' => true, 
         'options' => array( 
            'label' => 'Date Of Birth:',
            'format' => 'Y-m-d',
         ),
         'attributes' => [
           'max' => date("Y-m-d"),
           'step' => '1', // days; default step interval is 1 day
         ],
      )); 
      $this->add(array( 
         'name' => 'emp_img', 
         'type' => 'Text', 
         'required' => true, 
         'options' => array( 
            'label' => 'Profile Image:', 
         ), 
      )); 
      $this->add(array( 
         'name' => 'submit', 
         'type' => 'Submit', 
         'attributes' => array(
            'value' => 'Go', 
            'id' => 'submitbutton', 
         ), 
      )); 
   } 
} 