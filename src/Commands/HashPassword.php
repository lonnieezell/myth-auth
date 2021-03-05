<?php namespace Myth\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Myth\Auth\Entities\User;

class HashPassword extends BaseCommand
{
	protected $group = 'Auth';
	protected $name = 'auth:hash_password';
	protected $description = 'Hashes given password.';
	
	protected $usage = 'auth:hash_password [password]';
	protected $arguments = [
		'password' => 'Password value you want to hash.',
	];

	public function run(array $params = [])
	{
		// Consume or prompt for password
		$password = array_shift($params);

		if (empty($password))
		{
			$password = CLI::prompt('Password', null, 'required');
		}

		$user           = new User();
		$user->password = $password;

		// write to console
		CLI::write('Hash: ' . $user->password_hash, 'green');
	}
}
