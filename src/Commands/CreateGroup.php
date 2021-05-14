<?php namespace Myth\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateGroup extends BaseCommand
{
	protected $group       = 'Auth';
	protected $name        = 'auth:create_group';
	protected $description = "Adds a new group to the database.";

	protected $usage     = "auth:create_group [name] [description]";
	protected $arguments = [
		'name'        => "The name of the new group to create",
		'description' => "Optional description 'in quotes'",
	];

	public function run(array $params = [])
    {
		$auth = service('authorization');

		// consume or prompt for group name
		$name = array_shift($params);
		if (empty($name))
		{
			$name = CLI::prompt('Group name', null, 'required');
		}

		// consume or prompt for description
		$description = array_shift($params);
		if (empty($description))
		{
			$description = CLI::prompt('Description', '');
		}

		try
		{
			if (! $auth->createGroup($name, $description))
			{
				foreach ($auth->error() as $message)
				{
					CLI::write($message, 'red');
				}
			}
		}
		catch (\Exception $e)
		{
			$this->showError($e);
		}

		$this->call('auth:list_groups');
	}
}
