<?php namespace Myth\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Config\Services;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class CreateUser extends BaseCommand
{
    protected $group       = 'Auth';
    protected $name        = 'auth:create_user';
    protected $description = "Adds a new user to the database.";
    
	protected $usage     = "auth:create_user [username] [email]";
	protected $arguments = [
		'username'        => "The username of the new user to create",
		'email'           => "The email address of the new user to create",
	];

	public function run(array $params = [])
    {
    	$row = [];
    	
		// Consume or prompt for username
		$row['username'] = array_shift($params);
		if (empty($row['username']))
		{
			$row['username'] = CLI::prompt('Username', null, 'required');
		}
		
		// Consume or prompt for email
		$row['email'] = array_shift($params);
		if (empty($row['email']))
		{
			$row['email'] = CLI::prompt('Email', null, 'required');
		}

		// Save the user
		$users = new UserModel();
		$user = new User($row);

		if ($userId = $users->insert($user))
		{
			CLI::write(lang('Auth.registerCLI', [$row['username'], $userId]), 'green');
		}
		else
		{
			foreach ($users->errors() as $message)
			{
				CLI::write($message, 'red');
			}
		}
	}
}
