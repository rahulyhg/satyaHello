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
use Zend\Mail;  
use Zend\Mime;  
use Zend\Mime\Part as MimePart;  
use Zend\Mime\Message as MimeMessage; 

class PageController extends AbstractActionController
{
   public function indexAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function farestructureAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function aboutusAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function servicesAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function contactusAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function careerAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function copyrightAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function termsconditionAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function privacypolicyAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function faqAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function receptionAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function adminAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function accountantAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function accountant1Action()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function androidAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function phpdevAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function srphpdevAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function businessdevelopmentAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function digitalmarketingAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function regionalsalesmanagerAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function iosAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function areasalesmanagerAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function managerAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function webdesignerAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function quailtyanalystAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function salesexqutiveAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function teamleaderAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function vendorAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function documettionAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function refundpolicyAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function ticketsystemAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function billingsystemAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function partnerAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
		public function applynowAction()
    {
    	// $this->flashMessenger()->addMessage(array('error' => 'Custom error message to be here...'));
	   $user_session = new Container('user');       
       return new ViewModel(array('id'=>$user_session->username));
    }
	
	public function jobAction()
    {
		//echo "Mohit"; die;
	   $user_session = new Container('user');       
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


    public function sendmailAction()
    {

    	
                   
// $this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
$content = "<table border=1>
<tbody>
<tr>
<td>Name</td>
<td>'".$_POST['name']."'</td>
</tr>
<tr>
<td>Phone Number</td>
<td>".$_POST['ph_no']."</td>
</tr>
<tr>
<td>Email</td>
<td>".$_POST['email']."</td>
</tr>
<tr>
<td>Company Name</td>
<td>".$_POST['Company_name']."</td>
</tr>
<tr>
<td>Message</td>
<td>".$_POST['message']."</td>
</tr>
</tbody>
</table>";  
  
// make a header as html  
 
	$this->mailsetup($content);
	// if($status)
		echo "Message sent successfully"; 
	// else echo "failed";
    	exit();
    }

    public function carriermailAction()
    {	
    	$request = $this->getRequest();
        if($request->isPost()){

           $mergedata = array_merge(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

           $content = "<table border=1>
<tbody>
<tr>
<td>First Name</td>
<td>'".$_POST['Fname']."'</td>
</tr>
<tr>
<td>Last Name</td>
<td>'".$_POST['Lname']."'</td>
</tr>
<tr>
<td>Post Applied For :</td>
<td>'".$_POST['applied_post']."'</td>
</tr>
<tr>
<td>Phone Number</td>
<td>".$_POST['ph_no']."</td>
</tr>
<tr>
<td>Email</td>
<td>".$_POST['email']."</td>
</tr>
</tbody>
</table>";
	
	$options = new Mail\Transport\SmtpOptions(array(  
            'name' => 'localhost',  
            'host' => 'smtp.gmail.com',  
            'port'=> 587,  
            'connection_class' => 'login',  
            'connection_config' => array(  
                'username' => 'funstartswithyou15@gmail.com',  
                'password' => 'watchmyvideos',  
                'ssl'=> 'tls',  
            ),  
));  
      
$fileContents = fopen($mergedata['Upload_Resume']['tmp_name'], 'r');
$attachment = new Mime\Part($fileContents);
// $attachment->type = "message/rfc822" . "; name=".$mergedata['Upload_Resume']['name']."";
$attachment->type = 'application/pdf';
$attachment->disposition = Mime\Mime::DISPOSITION_ATTACHMENT;
$attachment->encoding = Mime\Mime::ENCODING_BASE64;
$attachment->filename = $mergedata['Upload_Resume']['name'];
    $attachment->id = $mergedata['Upload_Resume']['name'];

// $this->renderer = $this->getServiceLocator()->get('ViewRenderer');  
 
  
// make a header as html  
$html = new MimePart($content);  
$html->type = "text/html";  
$body = new MimeMessage();  
$body->setParts(array($html,$attachment));  
  
// instance mail   
$mail = new Mail\Message();  
$mail->setBody($body);
// $mail->getHeaders()->get('content-type')->setType('multipart/alternative');
$headers = $mail->getHeaders();
// $headers->removeHeader('Content-Type');
$headers->addHeaderLine('Content-Type', 'text/html; charset=UTF-8'); // will generate our code html from template.phtml  

$mail->setFrom('munanshu.madaank23@gmail.com','Sender Name');  
$mail->setTo('info@hello42cab.com');  
$mail->setSubject('Your Subject');  
  
$transport = new Mail\Transport\Smtp($options);  
$transport->send($mail);
	
	// echo "<script>alert('You've applied successfully we'll get back to you with in 24 hrs.');</script>";

		return $this->redirect()->toRoute('index/default', array('controller' => 'page', 'action' => 'apply_now'));		

        	// print_r($mergedata['Upload_Resume']['tmp_name']);
	
        } 
		exit();    	
    }

    public function mailsetup($content)
    {

    	$options = new Mail\Transport\SmtpOptions(array(  
            'name' => 'localhost',  
            'host' => 'smtp.gmail.com',  
            'port'=> 587,  
            'connection_class' => 'login',  
            'connection_config' => array(  
                'username' => 'funstartswithyou15@gmail.com',  
                'password' => 'watchmyvideos',  
                'ssl'=> 'tls',  
            ),  
		));  

		$html = new MimePart($content);  
		$html->type = "text/html";  
		$body = new MimeMessage();  
		$body->setParts(array($html,));  
  
// instance mail   
$mail = new Mail\Message();  
$mail->setBody($body); // will generate our code html from template.phtml  
$mail->setFrom('munanshu.madaank23@gmail.com','Sender Name');  
$mail->setTo('info@hello42cab.com');  
$mail->setSubject($_POST['subject']);  
  
$transport = new Mail\Transport\Smtp($options);  
$status = $transport->send($mail);


    }

    public function feedbackAction()
	{
		$reservation = "Call Center";

		if($_POST["reservation"]=="3"){

			$innercontent = "<tr>
<td>How do you rate the booking process</td>
<td>'".$_POST['Call_book_rate']."'</td>
</tr>
<tr>
<td>How do you rate our agent to answer your question </td>
<td>".$_POST['Call_agent_rate']."</td>
</tr>
<tr>
<td>How do you rate our agent to follow your instructions</td>
<td>".$_POST['Call_instruc_rate']."</td>
</tr>
<tr>
<td>How do you rate the confirmation process</td>
<td>".$_POST['Call_confirmation_rate']."</td>
</tr>
<tr>
<td>Over all how would you rate our services</td>
<td>".$_POST['Call_services_rate']."</td>
</tr>";

		}
else if($_POST["reservation"]=="2"){
		$reservation = "Android Application";


			$innercontent = "<tr>
<td>How do you rate our App</td>
<td>'".$_POST['And_app_rate']."'</td>
</tr>
<tr>
<td>How do you rate the booking process</td>
<td>".$_POST['And_book_rate']."</td>
</tr>
<tr>
<td>How do you rate the confirmation process</td>
<td>".$_POST['And_confirm_rate']."</td>
</tr>
<tr>
<td>Over all how would you rate our services </td>
<td>".$_POST['And_service_rate']."</td>
</tr>
";

		}
		else {
		$reservation = "Website";

			$innercontent = "<tr>
<td>How do you rate the booking process</td>
<td>'".$_POST['web_booking_rate']."'</td>
</tr>
<tr>
<td>How do you rate the confirmation process</td>
<td>".$_POST['web_confirmation_rate']."</td>
</tr>
<tr>
<td>Rate the process on website</td>
<td>".$_POST['web_process_rate']."</td>
</tr>
<tr>
<td>Over all how would you rate our services </td>
<td>".$_POST['web_service_rate']."</td>
</tr>
";

		}



		$content = "<table border=1>
<tbody>
<tr>
<td colspan='2'>".$resersvation."</td>
</tr>
".$innercontent."
<tr>
<td>How do you rate the reporting of vehicle </td>
<td>'".$_POST['vehicle_rate']."'</td>
</tr>
<tr>
<td>How do you rate the vehicle as requested</td>
<td>".$_POST['requested_rate']."</td>
</tr>
<tr>
<td>How was your special instructions followed </td>
<td>".$_POST['insfollow_rate']."</td>
</tr>
<tr>
<td>How was the vehicle condition </td>
<td>".$_POST['condition_rate']."</td>
</tr>
<tr>
<td>How was the Air-Conditioning effective</td>
<td>'".$_POST['effective_rate']."'</td>
</tr>
<tr>
<td>How was the cleanness and tidy of vehicle</td>
<td>".$_POST['cleanness_rate']."</td>
</tr>
<tr>
<td>How Was the uniform and grooming</td>
<td>".$_POST['grooming_rate']."</td>
</tr>
<tr>
<td>Over all how would you rate our services </td>
<td>".$_POST['And_service_rate']."</td>
</tr>
<tr>
<td>How do you rate the billing accuracy </td>
<td>".$_POST['accuracy_rate']."</td>
</tr>
<tr>
<td>How do you rate receiving invoice on time </td>
<td>".$_POST['invoice_rate']."</td>
</tr>
<tr>
<td>How will you rate your receiving invoice  </td>
<td>".$_POST['receivingInv_rate']."</td>
</tr>
</tbody>
</table>";  	


  
// make a header as html  
 	// print_r($content);
	$this->mailsetup($content);
	// // if($status)
	// 	echo "Message sent successfully"; 
	// // else echo "failed";
    	return $this->redirect()->toRoute('home', array( 
                        'controller' => 'Index', 
                        'action' =>  'index' 
                    ));
 	// die;
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
