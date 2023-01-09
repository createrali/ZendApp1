<?php  
namespace Employee\Model;  
use RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class EmployeeTable { 
   
   protected $tableGateway; 
   
   public function __construct(TableGatewayInterface $tableGateway) { 
      $this->tableGateway = $tableGateway; 
   }

   public function getadapter(){
      return $this->tableGateway->getAdapter();
   }
   
   public function fetchAll($paginated = false)
   {
      if ($paginated) {
         return $this->fetchPaginatedResults();
      }

      return $this->tableGateway->select();
   }

   private function fetchPaginatedResults()
   {
      // Create a new Select object for the table:
      $select = new Select($this->tableGateway->getTable());

      // Create a new result set based on the Album entity:
      $resultSetPrototype = new ResultSet();
      $resultSetPrototype->setArrayObjectPrototype(new Employee());

      // Create a new pagination adapter object:
      $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter(),$resultSetPrototype);

      $paginator = new Paginator($paginatorAdapter);
      return $paginator;
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

   public function deleteProfile($filename) {
      $filename = "public".$filename;
      $Message = '';
      if (file_exists($filename)) {
         if (!unlink($filename)) {
            return false;
        } else {
            return true;
        }
      } 

      return false;
   }
}    