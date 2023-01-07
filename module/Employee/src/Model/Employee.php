<?php  
namespace Employee\Model;  

use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface;  

class Employee implements InputFilterAwareInterface { 
   public $emp_id; 
   public $emp_name; 
   public $emp_address; 
   public $emp_email; 
   public $emp_phone; 
   public $emp_dob; 
   public $emp_img; 
   public $created_date; 
   public $update_date; 

   public function exchangeArray($data) { 
      $this->emp_id = (!empty($data['emp_id'])) ? $data['emp_id'] : null; 
      $this->emp_name = (!empty($data['emp_name'])) ? $data['emp_name'] : null; 
      $this->emp_address = (!empty($data['emp_address'])) ? $data['emp_address'] : null; 
      $this->emp_email = (!empty($data['emp_email'])) ? $data['emp_email'] : null; 
      $this->emp_phone = (!empty($data['emp_phone'])) ? $data['emp_phone'] : null; 
      $this->emp_dob = (!empty($data['emp_dob'])) ? $data['emp_dob'] : null; 
      $this->emp_img = (!empty($data['emp_img'])) ? $data['emp_img'] : null; 
   } 
    
   // Add content to these methods:
   public function setInputFilter(InputFilterInterface $inputFilter)    { 
      throw new \Exception("Not used"); 
   }  

   public function getInputFilter() { 
      if (!$this->inputFilter) { 
         $inputFilter = new InputFilter();  
         $inputFilter->add([ 
            'name' => 'emp_id', 
            'required' => true, 
            'filters' => [ 
               ['name' => 'Int'], 
            ], 
         ]);  
         $inputFilter->add([ 
            'name' => 'emp_name', 
            'required' => true, 
            'filters' => [ 
               ['name' => 'StripTags'], 
               ['name' => 'StringTrim'], 
            ], 
            'validators' => [ 
               ['name' => 'StringLength', 
                        'options' => [ 
                           'encoding' => 'UTF-8', 
                           'min' => 1, 
                           'max' => 10, 
                        ], 
                    ], 
                ], 
            ]);
         $inputFilter->add([ 
            'name' => 'emp_address', 
            'required' => true, 
            'filters' => [ 
               ['name' => 'StripTags'], 
               ['name' => 'StringTrim'], 
            ], 
            'validators' => [ 
               ['name' => 'StringLength', 
                        'options' => [ 
                           'encoding' => 'UTF-8', 
                           'min' => 1, 
                           'max' => 300, 
                        ], 
                    ], 
                ], 
            ]);
         $inputFilter->add([ 
            'name' => 'emp_email', 
            'required' => true, 
            'filters' => [ 
               ['name' => 'StripTags'], 
               ['name' => 'StringTrim'], 
            ], 
            'validators' => [ 
               ['name' => 'StringLength', 
                        'options' => [ 
                           'encoding' => 'UTF-8', 
                           'min' => 1, 
                           'max' => 100, 
                        ], 
                    ], 
                ], 
            ]);
         $inputFilter->add([ 
            'name' => 'emp_phone', 
            'required' => true, 
            'filters' => [ 
               ['name' => 'StripTags'], 
               ['name' => 'StringTrim'], 
            ], 
            'validators' => [ 
               ['name' => 'StringLength', 
                        'options' => [ 
                           'encoding' => 'UTF-8', 
                           'min' => 1, 
                           'max' => 10, 
                        ], 
                    ], 
                ], 
            ]);
         $inputFilter->add([ 
            'name' => 'emp_dob', 
            'required' => true, 
            'filters' => [ 
               ['name' => 'StripTags'], 
               ['name' => 'StringTrim'], 
            ], 
            'validators' => [ 
               ['name' => 'StringLength', 
                        'options' => [ 
                           'encoding' => 'UTF-8', 
                           'min' => 1, 
                           'max' => 10, 
                        ], 
                    ], 
                ], 
            ]);
         $inputFilter->add([ 
            'name' => 'emp_img', 
            'required' => true, 
            'filters' => [ 
               ['name' => 'StripTags'], 
               ['name' => 'StringTrim'], 
            ], 
            'validators' => [ 
               ['name' => 'StringLength', 
                        'options' => [ 
                           'encoding' => 'UTF-8', 
                           'min' => 1, 
                           'max' => 10, 
                        ], 
                    ], 
                ], 
            ]);
         $this->inputFilter = $inputFilter; 
      } 
      return $this->inputFilter; 
   } 

   public function getArrayCopy() { 
      return get_object_vars($this); 
   } 
}