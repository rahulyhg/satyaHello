<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Index\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class CareerController extends AbstractActionController
{
	public function indexAction(){
		$user_session = new Container('user');
		return new ViewModel(array('id'=>$user_session->username));
	}
	public function logoutAction(){
		$user_session = new Container('user');
		$user_session->username="";
		return $this->redirect()->toRoute('home', array( 
			'controller' => 'Index', 
			'action' =>  'index' 
		));
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
