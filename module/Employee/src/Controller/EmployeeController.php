<?php  
namespace Employee\Controller;  
use Zend\Mvc\Controller\AbstractActionController; 
use Zend\View\Model\ViewModel;  
use Employee\Model\Employee; 
use Employee\Model\EmployeeTable;    
use Employee\Form\EmployeeForm;

class EmployeeController extends AbstractActionController { 
   private $table;  
   public function __construct(EmployeeTable $table) { 
      $this->table = $table; 
   }
     
   public function indexAction() { 
      // Grab the paginator from the AlbumTable:
      $paginator = $this->table->fetchAll(true);

      // Set the current page to what has been passed in query string,
      // or to 1 if none is set, or the page is invalid:
      $page = (int) $this->params()->fromQuery('page', 1);
      $page = ($page < 1) ? 1 : $page;
      $paginator->setCurrentPageNumber($page);

      // Set the number of items per page to 10:
      $paginator->setItemCountPerPage(10);

      return new ViewModel(['paginator' => $paginator]);
   } 

   public function addAction() { 

      $form = new EmployeeForm();  
      $form->get('submit')->setValue('Add');  
      $request = $this->getRequest(); 
      
      if ($request->isPost()) { 
         $employee = new Employee(); 
         $employee->setadapter($this->table->getadapter());
         $form->setInputFilter($employee->getInputFilter()); 

         $post = array_merge_recursive( 
            $request->getPost()->toArray(), 
            $request->getFiles()->toArray() 
         );

         $form->setData($post);  
         
         if ($form->isValid()) { 
            $employee->exchangeArray($form->getData());
            $this->table->saveEmployee($employee);  
            
            // Redirect to list of employees 
            return $this->redirect()->toRoute('employee'); 
         } 
      } 
      return array('form' => $form); 
   }

   public function editAction() { 

      $emp_id = (int) $this->params()->fromRoute('emp_id', 0); 
      if (!$emp_id) { 
         return $this->redirect()->toRoute('employee', array( 
            'action' => 'add' 
         )); 
      }  
      try { 
         $employee = $this->table->getEmployee($emp_id); 
         $employee->old_image = $employee->emp_img;
         $employee->setadapter($this->table->getadapter());

      } catch (\Exception $ex) { 
         return $this->redirect()->toRoute('employee', array( 
            'action' => 'index' 
         )); 
      }  
      $form = new EmployeeForm(); 
      $form->bind($employee); 
      $form->get('submit')->setAttribute('value', 'Edit');  
      $request = $this->getRequest(); 
      
      if ($request->isPost()) { 
         $form->setInputFilter($employee->getInputFilter()); 
         $post = array_merge_recursive( 
            $request->getPost()->toArray(), 
            $request->getFiles()->toArray() 
         );

         $form->setData($post); 
         if ($form->isValid()) { 

            if(empty($employee->emp_img) && !empty($employee->old_image)) {
               $employee->emp_img = $employee->old_image;
            }
            $this->table->saveEmployee($employee);  

            if(!empty($employee->emp_img) && $employee->old_image != $employee->emp_img){
               $this->table->deleteProfile($employee->old_image);
            }
            
            // Redirect to list of employees 
            return $this->redirect()->toRoute('employee'); 
         } 
      }  
      return array('emp_id' => $emp_id, 'form' => $form,); 
   }


   public function deleteAction() { 
      $emp_id = (int) $this->params()->fromRoute('emp_id', 0); 
      if (!$emp_id) { 
         return $this->redirect()->toRoute('employee'); 
      }  
      $request = $this->getRequest(); 
      if ($request->isPost()) { 
         $del = $request->getPost('del', 'No');  
         if ($del == 'Yes') { 
            $emp_id = (int) $request->getPost('emp_id');
            try { 
               $employee = $this->table->getEmployee($emp_id);
               $this->table->deleteProfile($employee->emp_img);
               $this->table->deleteEmployee($emp_id);  
            } catch (\Exception $ex) { 
               return $this->redirect()->toRoute('employee', array( 
                  'action' => 'index' 
               )); 
            }
         } 
         return $this->redirect()->toRoute('employee'); 
      }  
      return array( 
         'emp_id' => $emp_id, 
         'employee' => $this->table->getEmployee($emp_id) 
      ); 
   }
}