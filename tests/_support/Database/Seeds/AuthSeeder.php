<?php namespace ModuleTests\Support\Database\Seeds;

use Myth\Auth\Entities\User;

class AuthSeeder extends \CodeIgniter\Database\Seeder
{
	public function run()
	{
		// USERS
		$users = [
			[
				'email'            => 'yamira@noted.com',
				'username'         => 'light',
				'password'         => 'secretK33P3R',
			],
			[
				'email'            => 'kazuto.kirigaya@castle.org',
				'username'         => 'kirito',
				'password'         => 'swordsX2',
			],
			[
				'email'            => 'Mittelman@example.com',
				'username'         => 'Saitama',
				'password'         => '1punch',
			],
        ];
		
		$builder = $this->db->table('users');
		
		foreach ($users as $user)
		{
			// Use the User entity to handle correct password hashing
			$user = new User($user);
			$builder->insert($user->toArray());
		}
		
		// GROUPS
		$groups = [
			[
				'name'            => 'Administrators',
				'description'     => 'Users with ultimate power',
			],
			[
				'name'            => 'Blacklisted',
				'description'     => 'Users sequestered for misconduct',
			],
			[
				'name'            => 'Puny',
				'description'     => 'Users who can do next to nothing',
			],
        ];
		
		$builder = $this->db->table('auth_groups');
		
		foreach ($groups as $group)
		{
			$builder->insert($group);
		}
		
		// GROUPS-USERS
		$rows = [
			[
				'group_id'    => 1,
				'user_id'     => 1,
			],
			[
				'group_id'    => 2,
				'user_id'     => 1,
			],
			[
				'group_id'    => 3,
				'user_id'     => 2,
			],
        ];
		
		$builder = $this->db->table('auth_groups_users');
		
		foreach ($rows as $row)
		{
			$builder->insert($row);
		}
	}
}
