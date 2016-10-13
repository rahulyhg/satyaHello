<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
   public function indexAction()
    {	
    	 
		$user_session = new Container('user');
        return new ViewModel(array('id'=>$user_session->username));
    }
	public function packageAction()
    {	
		
		 $user_session = new Container('user');
        return new ViewModel(array('id'=>$user_session->username));
    }
	public function traveldetailAction()
    {	
		
		 $user_session = new Container('user');
        return new ViewModel(array('id'=>$user_session->username));
    }
	public function dashboardAction()
    {	
		
		 $user_session = new Container('user');
        return new ViewModel(array('id'=>$user_session->username));
    }
	public function logout()
	{
		 session_destroy();
   		 session_unset();
		 $this->indexAction();
	}
        public function carreerAction()
	{
	return new ViewModel();
	}

	
}
