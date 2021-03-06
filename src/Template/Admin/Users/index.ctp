<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace src/Template/Pages/home.ctp with your own version or re-enable debug mode.'
    );
endif;

$cakeDescription = 'CakePHP: the rapid development PHP framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Users List
    </title>

    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('home.css') ?>
    <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
</head>
<body class="home">
	<div class="row">
		<div class="columns large-8">
			<?= $this->Flash->render() ?>
		</div>
	</div>
	<div class="row">
		<div class="columns large-12">
			<h1>Users</h1>
<p><?= $this->Html->link("Add User", ['action' => 'add']) ?></p>
<table>
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
    </tr>

<!-- Here's where we iterate through our $articles query object, printing out article info -->

<?php foreach ($users as $user): ?>
    <tr>
        <td>
            <?php echo $user->username;?>
        </td>
        <td>
            <?php echo $user->email;?>
        </td>
		<td>
            <?php echo ucfirst($user->role);?>
        </td>
        <td>
			<?php if(empty($user->deleted_date)):?>
			<?= $this->Html->link('Edit', ['action' => 'edit', $user->id]) ?> |
			<?php else:?>
			<?= 'Edit';?> |
			<?php endif;?>	
            <?= 
				
				$this->Form->postLink(
                (empty($user->deleted_date)) ? "Delete" : "Restore",
                ['action' => 'delete', $user->id],
                ['confirm' => 'Are you sure?'])
            ?>
        </td>
    </tr>
<?php endforeach; ?>

</table>


		</div>
	</div>

</body>
</html>
