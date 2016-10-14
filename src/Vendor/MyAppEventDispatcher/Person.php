<?php


namespace Vendor\MyAppEventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


require './vendor/autoload.php';

class User {
	
	private $Email ;
	public function setEmail($Email) {
		$this->Email = $Email;
		return $this;
	}
	 
}

class CreateUserEvent extends Event {
	protected $newUser;
	protected $currentRadv;

	public function __construct(User $newUser, User $currentRadv){
		$this->newUser = $newUser;
		$this->currentRadv = $currentRadv;
	}

	public function getNewUser()
	{
		return $this->newUser;
	}

	public function getCurrentRadv()
	{
		return $this->currentRadv;
	}
}



class CreateUserEventListener
{
    public function askAffectationAdv(CreateUserEvent $event)
    {
        global $firephp;
		global $log ;
		$log = new Logger('EventDispatcher');
		$log->pushHandler(new StreamHandler('/tmp/log.log', Logger::WARNING));
		// add records to the log
		$log->warning('Foo');
		$log->error('Bar');
    }
}


class AppMain extends Controller {
	
	public function uploadAction()
	{
		$dispatcher = new EventDispatcher();
		
		$freeCallListener = new CreateUserEventListener();
		$dispatcher->addListener('nadir', array($freeCallListener, 'askAffectationAdv'));
		

		$newUser = new User();
		$newUser->setEmail("newuser@appventus.com");
		$currentRADV = new User();
		$currentRADV->setEmail("current-radv@appventus.com");
		
		// create the Event and dispatch it
		$event = new CreateUserEvent($newUser,$currentRADV);
		$dispatcher->dispatch('nadir', $event);
		/*
		$dispatcher = new EventDispatcher();
$freeCallListener = new FreedomListener();
$dispatcher->addListener(BillingEvents::onFreeCallBill, array($freeCallListener, 'onFreeCall'));



$bill = new Bill() ;
$event = new FilterBillEvent($bill) ;
$dispatcher->dispatch(BillingEvents::onFreeCallBill, $event);
*/

		
	}
	
}


$instance = new AppMain() ; 
$instance->uploadAction() ; 