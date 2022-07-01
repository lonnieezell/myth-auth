<?php

namespace Myth\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ListGroups extends BaseCommand
{
    protected $group       = 'Auth';
    protected $name        = 'auth:list_groups';
    protected $description = 'Lists groups from the database.';
    protected $usage       = 'auth:list_groups';

    public function run(array $params)
    {
        $db = db_connect();

        // get all groups
        $rows = $db->table('auth_groups')
            ->select('id, name, description')
            ->orderBy('name', 'asc')
            ->get()->getResultArray();

        if (empty($rows)) {
            CLI::write(CLI::color('There are no groups.', 'yellow'));
        } else {
            $thead = ['Group ID', 'Name', 'Description'];
            CLI::table($rows, $thead);
        }
    }
}
