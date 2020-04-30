<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;

class UsersController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->Auth->allow(['logout', 'register']);
	}
	
	public function login()
	{
		
		//$hasher = new DefaultPasswordHasher();

		//echo $password = $hasher->hash('admin');exit;
			
			
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->Flash->error('Your username or password is incorrect.');
		}
	}
	
	public function register()
	{
		$user   =   $this->Users->newEntity();
		
		
		$email = new Email();
				$email
				//->template('welcome', 'fancy')
				->emailFormat('html')
				->to('iamirfanwebdeveloper@gmail.com')
				->from('imnawabkhan6@gmail.com')
				->subject('Register Mail')
				->send("Welcome to our abc.com thank your very much for register");
				exit;
			
		if ($this->request->is('post')) {
			
			$user = $this->Users->patchEntity($user, $this->request->getData());
			
			$hasher = new DefaultPasswordHasher();

			$password = $hasher->hash($this->request->getData('password'));
		
			/*$user->username = $this->request->getData('username');
			$user->email = $this->request->getData('email');
			$user->password = $password;
			$user->role = 'user';*/
			$user->role = 'user';
			if ($this->Users->save($user)) {
				$this->Flash->success('You are successfully register');
				
				/*$email = new Email();
				$email
				//->template('welcome', 'fancy')
				->emailFormat('html')
				->to('iamirfanwebdeveloper@gmail.com')
				->from('imnawabkhan6@gmail.com')
				->subject('Register Mail')
				->send("Welcome to our abc.com thank your very much for register");*/
				
				return $this->redirect(['action' => 'login']);
			}
		}
		
		$this->set('user', $user);
	}
	
	public function logout()
	{
		$this->Flash->success('You are now logged out.');
		return $this->redirect($this->Auth->logout());
	}
}
