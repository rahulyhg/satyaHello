<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
class DriverController extends AbstractActionController
{
   public function indexAction()
    {
       $user_session = new Container('user');
       //print_r($user_session->username);
       $this->layout()->setTemplate('layout/layout_user');
        return new ViewModel(array('id'=>$user_session->username));
    }
     public function logoutAction()
    {
         $user_session = new Container('user');
         $user_session->username="";
           return $this->redirect()->toRoute('home', array( 
                        'controller' => 'Index', 
                        'action' =>  'index' 
                    ));
    }
    
	public function changepasswordAction(){
        
        return new ViewModel();
        
    }
	
	public function driverorderhistoryAction(){
        
        return new ViewModel();
        
    }
	
	public function credithistoryAction(){
        
        return new ViewModel();
        
    }
	public function drivereditprofileAction(){
        
        return new ViewModel();
        
    }
	public function accountbalanceAction(){
        
        return new ViewModel();
        
    }
	public function transactionAction(){
        
        return new ViewModel();
        
    }
	public function markupmanagementAction(){
        
        return new ViewModel();
        
    }
	public function vehicleupdateAction(){
        
        return new ViewModel();
        
    }
	public function creditrequestAction(){
        
        return new ViewModel();
        
    }
	public function bankdetailAction(){
        
        return new ViewModel();
        
    }
	/*protected $collectionOption = array('GET','POST');
	protected $resourceOptions = array('GET','PUT','DELETE');
	
	protected function _getOptions()
	{
		if($this->params->formRoute('id',false)){
			return $this->resourceOptions;
		}
		return $this->collectionOption;
	}
	
	public function options()
	{
		$response = $this->getResponse();
		$response->getHeader()
				 ->addHeaderLine('Allow',implode(',',$this->_getOptions()));
		return $response;
	
	}
	public function setEventManager(EventManagerInterface $events){
		
		$this->events = $events;
		$events->attach('dispatch', array($this,'checkOptions'),10);
	}
	public function checkOptions($e)
	{
		if(in_array($e->getRequest()->getMethod(), $this->_getOptions())){
		
			return;
		}
		$response = $this->getResponse();
		$response->setStatusCode(405);
		return $response;
	}
	public function create($data){
	
		$userAPIService = $this->$getServicelLocator()->get('userAPIService');
		$result = $userAPIService->create($data);
		$response->setStatusCode(201);
		return new JsonModel($result);
	}*/
}
