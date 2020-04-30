<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class RegisterForm extends Form
{
    protected function _buildSchema(Schema $schema)
    {
        return $schema
			->addField('username', 'string')
            ->addField('email', ['type' => 'string'])
            ->addField('password', ['type' => 'string']);
    }

    protected function validationDefault(Validator $validator)
    {
        $validator->add('name', 'length', [
                'rule' => ['minLength', 10],
                'message' => 'A name is required'
            ])->add('email', 'format', [
                'rule' => 'email',
                'message' => 'A valid email address is required',
            ]);

        return $validator;
    }

    protected function _execute(array $data)
    {
        // Send an email.
        return true;
    }
}
