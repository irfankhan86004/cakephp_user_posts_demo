<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Routing\Router;

class LoginNotificationCommand extends Command
{
	public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
    }
	
    public function execute(Arguments $args, ConsoleIo $io)
    {
		$user = $this->Users->find();
		
		foreach ($user as $value) {
			
			$email = new Email();
				$email
				->emailFormat('html')
				->to('iamirfanwebdeveloper@gmail.com')
				->from('info@gmail.com')
				->subject('User login notification')
				->send("Please click to login link <a href='http://127.0.0.1/tiger/tiger/users/'>Login</a> thank you ");
		}

		$io->out('Send mail to all users');
    }
}