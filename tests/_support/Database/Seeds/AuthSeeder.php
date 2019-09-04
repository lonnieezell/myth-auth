<?php namespace CIModuleTests\Support\Database\Seeds;

class AuthSeeder extends \CodeIgniter\Database\Seeder
{
	public function run()
	{
		// USERS
		$users = [
			[
				'email'            => 'yamira@noted.com',
				'username'         => 'light',
			],
			[
				'email'            => 'kazuto.kirigaya@castle.org',
				'username'         => 'kirito',
			],
			[
				'email'            => 'Mittelman@example.com',
				'username'         => 'Saitama',
			],
        ]);
		
		$builder = $this->db->table('users');
		
		foreach ($users as $user)
		{
			$builder->insert($user);
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
        ]);
		
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
        ]);
		
		$builder = $this->db->table('auth_groups_users');
		
		foreach ($rows as $row)
		{
			$builder->insert($row);
		}
	}
}
