<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher; // Add this line

class ArticlesController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		
		$this->Auth->allow(['no_access']);
		
		if(!$this->Auth->user()){
			$this->Flash->error(__('Please login after access page!'));
			return $this->redirect('/users/login');
		}
		
		$this->userId = $this->Auth->user()['id'];
	}
	
	public function isAuthorized($user)
	{
		// All other actions require a slug.
		$slug = $this->request->getParam('pass.0');
		if (!$slug) {
			return false;
		}
		// Check that the article belongs to the current user.
		$article = $this->Articles->findBySlug($slug)->where(['user_id' => $this->userId])->first();

		return $article->user_id === $user['id'];
	}
	
	public function noAccess()
    {
		
	}
	
	public function index()
    {	
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find()->where(['user_id' => $this->userId]));
        $this->set(compact('articles'));
    }	
	
	public function view($slug = null)
	{
		if ($this->isAuthorized($this->Auth->user())) {
			
			return $this->redirect('articles/no_access');	
		}
		
		$article = $this->Articles->findBySlug($slug)->where(['user_id' => $this->userId])->firstOrFail();
		$this->set(compact('article'));
	}

	public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
  		
		    $article->user_id = $this->Auth->user()['id'];

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your post has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your post.'));
        }
        $this->set('article', $article);
    }
	
	public function edit($slug)
	{
		$article = $this->Articles->findBySlug($slug)->firstOrFail();
		if ($this->request->is(['post', 'put'])) {
			$this->Articles->patchEntity($article, $this->request->getData());
			if ($this->Articles->save($article)) {
				$this->Flash->success(__('Your post has been updated.'));
				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('Unable to update your post.'));
		}

		$this->set('article', $article);
	}
	
	public function delete($slug)
	{
		$this->request->allowMethod(['post', 'delete']);
		
		if (!$this->isAuthorized($this->Auth->user())) {
			return $this->redirect('/articles/no_access');	
		}

		$article = $this->Articles->findBySlug($slug)->where(['user_id' => $this->userId])->firstOrFail();
		if ($this->Articles->delete($article)) {
			$this->Flash->success(__('The {0} post has been deleted.', $article->title));
			return $this->redirect('/articles');
		}
	}
}
