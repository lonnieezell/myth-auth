<?php namespace Myth\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Myth\Auth\Models\UserModel;

class SetPassword extends BaseCommand
{
	protected $group = 'Auth';
	protected $name = 'auth:set_password';
	protected $description = 'Set password to user.';

	protected $usage = 'auth:set_password [identity] [password]';
	protected $arguments = [
		'identity' => 'User identity.',
		'password' => 'Password value you want to set.',
	];

	public function run(array $params = [])
	{
		// Consume or prompt for password
		$identity = isset($params[0]) ? $params[0] : null;
		$password = isset($params[1]) ? $params[1] : null;

		if (empty($identity))
		{
			$identity = CLI::prompt('Identity', null, 'required');
		}

		if (empty($password))
		{
			$password = CLI::prompt('Password', null, 'required');
		}

		$type = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		$userModel = new UserModel();
		$user      = $userModel->where($type, $identity)->first();

		if (! $user)
		{
            CLI::write('User with identity: '. $identity .' not found.', 'red');
		}
		else
		{
			$user->password = $password;

			if ($userModel->save($user))
			{
                CLI::write('Password successfully set for user with identity: ' . $identity , 'green');
			}
			else
			{
                CLI::write('Failed to set password for user with identity: ' . $identity , 'red');
			}
		}
	}
}
