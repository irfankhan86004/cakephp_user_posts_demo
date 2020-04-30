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
use App\Model\Entity\User;

class AdminController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->Auth->allow(['userList', 'no_access']);
		
		if(!$this->Auth->user()){
			$this->Flash->error(__('Please login after access page!'));
			return $this->redirect('/users/login');
		}
		
		if($this->Auth->user()['role'] != 'admin'){
			$this->Flash->error(__('You have not access of this page!'));
			return $this->redirect('/articles/no_access');
		}
		
	}
	
	public function index()
    {

    }
	
	public function noAccess()
    {
		$this->render('Users/no_access');
	}
	
	public function userList()
	{
		$this->loadComponent('Paginator');
		
		$users = TableRegistry::getTableLocator()->get('users');

		$allRecords = $users->find('all', ['withDeleted']);
        $users = $this->Paginator->paginate($allRecords);
		
        $this->set(compact('users'));
		
		$this -> render('Users/index');
	}
	
	public function add()
    {
		$userTable = TableRegistry::getTableLocator()->get('users');
		
		$userNewEntity = $userTable->newEntity();
		
		if ($this->request->is('post')) {
			$user = $userTable->patchEntity($userNewEntity, $this->request->getData());
			
			$hasher = new DefaultPasswordHasher();

			$password = $hasher->hash($this->request->getData('password'));
		
			$user->username = $this->request->getData('username');
			$user->email = $this->request->getData('email');
			$user->password = $password;
			$user->role = 'user';

			if ($userTable->save($user)) {
				$this->Flash->success('Your user has been saved');
				
				return $this->redirect(['action' => 'user-list']);
			}
		}
        $this->set('user', $userNewEntity);
        $this->set('button', 'Add');
		$this->set('header', 'Add User');
		$this -> render('Users/add');
    }
	
	public function edit($id)
	{
		$userTable = TableRegistry::getTableLocator()->get('users');
		$user = $userTable->find()->where(['id' => $id])->firstOrFail();
		if ($this->request->is(['post', 'put'])) {
			$user = $userTable->patchEntity($user, $this->request->getData());
			
			$hasher = new DefaultPasswordHasher();

			$password = $hasher->hash($this->request->getData('password'));
			
			$user->username = $this->request->getData('username');
			$user->email = $this->request->getData('email');
			$user->password = $password;
			$user->role = 'user';
			
			if ($userTable->save($user)) {
				$this->Flash->success(__('Your user has been updated.'));
				return $this->redirect(['action' => 'user-list']);
			}
			$this->Flash->error(__('Unable to update your user.'));
		}

		$this->set('user', $user);
        $this->set('button', 'Update');
        $this->set('header', 'Edit User');
		$this -> render('Users/add');
	}
	
	public function delete($id)
	{
		$users = TableRegistry::getTableLocator()->get('users');
		
		$userObject = $users->find('all', ['withDeleted'])->where(['id' => $id])->first();

		if (empty($userObject->deleted_date)) {
			$users->delete($userObject);
			$message = 'Soft deleted this user';	
		} else {
			$users->restore($userObject);
			$message = 'Restore this user';
		}
		
		$this->Flash->success($message);
		
		return $this->redirect(['action' => 'user-list']);
		
	}
}
