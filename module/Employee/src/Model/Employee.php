<?php  
namespace Employee\Model;  

use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface;  
use Zend\Db\Adapter\Adapter;
use Zend\Validator\File\Extension;
use Zend\Db\Adapter\AdapterInterface;

class Employee implements InputFilterAwareInterface { 
   public $emp_id = 0; 
   public $emp_name; 
   public $emp_address; 
   public $emp_email; 
   public $emp_phone; 
   public $emp_dob; 
   public $emp_img; 
   public $created_date; 
   public $update_date; 
   public $old_image;

   public $adapter;

   public function setadapter($adapter) {
      $this->adapter = $adapter;
   }

   public function exchangeArray($data) { 
      $this->emp_id = (!empty($data['emp_id'])) ? $data['emp_id'] : null; 
      $this->emp_name = (!empty($data['emp_name'])) ? $data['emp_name'] : null; 
      $this->emp_address = (!empty($data['emp_address'])) ? $data['emp_address'] : null; 
      $this->emp_email = (!empty($data['emp_email'])) ? $data['emp_email'] : null; 
      $this->emp_phone = (!empty($data['emp_phone'])) ? $data['emp_phone'] : null; 
      $this->emp_dob = (!empty($data['emp_dob'])) ? $data['emp_dob'] : null; 
      
      if(!empty($data['emp_img'])) { 
         if(is_array($data['emp_img'])) { 
            $this->emp_img = str_replace("./public", "", 
               $data['emp_img']['tmp_name']); 
         } else { 
            $this->emp_img = $data['emp_img']; 
         } 
      } else { 
         $data['emp_img'] = null; 
      } 
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
               [
                  'name' => 'StringLength', 
                  'options' => [ 
                     'encoding' => 'UTF-8', 
                     'min' => 3, 
                     'max' => 20,
                     'messages' => [
                        'stringLengthTooShort' => 'Please enter Employee Name with minimum 3 character!',
                        'stringLengthTooLong' => 'Please enter Employee Name with maximum 20 character!',
                     ]
                  ], 
               ], 
               [
                  'name' => 'Alpha',
                  'options' => [ 
                     'allowWhiteSpace' => true
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
               [
                  'name' => 'StringLength', 
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
               [
                  'name' => 'StringLength', 
                  'options' => [ 
                     'encoding' => 'UTF-8', 
                     'min' => 1, 
                     'max' => 100, 
                  ], 
               ],
               [
                  'name' => 'EmailAddress',
                  'options' => [
                     'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                     'useMxCheck' => false,                            
                  ]
               ],
               [
                  'name'=>'Zend\Validator\Db\NoRecordExists',
                  'options' => [
                     'table' => 'employee', 
                     'field' => 'emp_email',
                     'adapter' => $this->adapter,
                     'exclude' => [
                        'field' => 'emp_id',
                        'value' => $this->emp_id,
                     ],
                     'messages' => [
                        'recordFound' => 'Email already taken'
                     ]
                  ]
               ]
            ]
         ]);
         $inputFilter->add([ 
            'name' => 'emp_phone', 
            'required' => true, 
            'filters' => [ 
               ['name' => 'StripTags'], 
               ['name' => 'StringTrim'], 
            ], 
            'validators' => [ 
               [
                  'name' => 'StringLength', 
                  'options' => [ 
                     'encoding' => 'UTF-8', 
                     'min' => 10, 
                     'max' => 10, 
                  ], 
               ],
               [
                  'name' => 'PhoneNumber' 
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
               [
                  'name' => 'Date', 
                  'options' => [
                  ], 
               ], 
            ], 
         ]);

         $inputFilter->add([ 
            'name' => 'emp_img', 
            'required' => false, 
            'filters' => [ 
               [
                  'name' => 'Zend\Filter\File\RenameUpload',
                  'options' => [
                     'target'    => './public/img/employee', 
                     'randomize' => true, 
                     'use_upload_extension' => true 
                  ]
               ] 
            ], 
            'validators' => [
               [
                  'name' => 'Zend\Validator\File\Extension',
                  'options' => [
                     'extension' => ['jpg', 'png', 'jpeg'],
                     'case' => true
                  ]
               ],
               [
                  'name' => 'Zend\Validator\File\MimeType',
                  'options' => [
                     'mimeType' => ['image/png', 'image/jpeg']
                  ]
               ],
               [
                  'name' => 'Zend\Validator\File\Size',
                  'options' => [
                     'max' => '2MB'
                  ]
               ],
               ['name' => 'Zend\Validator\File\UploadFile'], 
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