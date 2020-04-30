<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;

class UsersTable extends Table {
	
	use SoftDeleteTrait;

    protected $softDeleteField = 'deleted_date';
	
	public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }
	
	public function validationDefault(Validator $validator)
	{
		$validator
			->allowEmptyString('username', false)
			->allowEmptyString('password', false)
			->allowEmptyString('email', false)
			;

		return $validator;
	}
}
?>