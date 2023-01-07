<?php  
namespace Employee\Model;  
use Zend\Db\TableGateway\TableGatewayInterface;  

class EmployeeTable { 
   
   protected $tableGateway; 
   
   public function __construct(TableGatewayInterface $tableGateway) { 
      $this->tableGateway = $tableGateway; 
   }

   public function getadapter(){
      return $this->tableGateway->getAdapter();
   }
   
   public function fetchAll() { 
      $resultSet = $this->tableGateway->select();  
      return $resultSet; 
   }

   public function getEmployee($id) { 
      $emp_id  = (int) $id; 
      $rowset = $this->tableGateway->select(array('emp_id' => $emp_id)); 
      $row = $rowset->current();  
      if (!$row) { 
         throw new \Exception("Could not find row $emp_id"); 
      }
      return $row; 
   }  
   
   public function saveEmployee(Employee $employee) { 
      $data = array (  
         'emp_name' => $employee->emp_name, 
         'emp_address' => $employee->emp_address, 
         'emp_email' => $employee->emp_email, 
         'emp_phone' => $employee->emp_phone, 
         'emp_dob' => $employee->emp_dob, 
         'emp_img' => $employee->emp_img 
      );  
      $emp_id = (int) $employee->emp_id; 
      if ($emp_id == 0) { 
         $this->tableGateway->insert($data); 
      } else { 
         if ($this->getEmployee($emp_id)) { 
            $data['update_date'] = date("Y-m-d H:i:s");
            $this->tableGateway->update($data, array('emp_id' => $emp_id)); 
         } else { 
            throw new \Exception('Employee id does not exist'); 
         } 
      } 
   } 

   public function deleteEmployee($emp_id) { 
      $this->tableGateway->delete(['emp_id' => (int) $emp_id]); 
   }
}    