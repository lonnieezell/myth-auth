<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_alter_table_users extends Migration
{
	public function up()
	{		
		// add new identity info
		$fields = [
			'firstname'      => ['type' => 'VARCHAR', 'constraint' => 63, 'after' => 'username'],
			'lastname'       => ['type' => 'VARCHAR', 'constraint' => 63, 'after' => 'firstname'],
			'phone'          => ['type' => 'VARCHAR', 'constraint' => 63, 'after' => 'lastname'],
		];
		$this->forge->addColumn('users', $fields);
	}

	public function down()
	{
		// drop new columns
		$this->forge->dropColumn('users', 'firstname');
		$this->forge->dropColumn('users', 'lastname');
		$this->forge->dropColumn('users', 'phone');
	}
}
