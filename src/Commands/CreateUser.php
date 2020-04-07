<?php

namespace Myth\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Config\Services;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class CreateUser extends BaseCommand {

    protected $group = 'Auth';
    protected $name = 'auth:create_user';
    protected $description = "Adds a new user to the database.";
    protected $usage = "auth:create_user [username] [email]";
    protected $arguments = [
        'username' => "The username of the new user to create",
        'email' => "The email address of the new user to create",
        'password' => "The password of the new user to create"
    ];

    public function run(array $params = []) {
        $row = [];

        // Consume or prompt for username
        $row['username'] = array_shift($params);
        if (empty($row['username'])) {
            $row['username'] = CLI::prompt('Username', null, 'required');
        }

        // Consume or prompt for email
        $row['email'] = array_shift($params);
        if (empty($row['email'])) {
            $row['email'] = CLI::prompt('Email', null, 'required');
        }
        
        // Consume or prompt for password
        $password_plain = array_shift($params);
        if (empty($password_plain)) {
            $password_plain = CLI::prompt('Password', null, 'required');
        }
        $row['password_hash'] = $this->setPassword($password_plain);
        $row['active'] = 1;

        // Save the user
        $users = new UserModel();
        $user = new User($row);

        if ($userId = $users->insert($user)) {
            CLI::write(lang('Auth.registerCLI', [$row['username'], $userId]), 'green');
        } else {
            foreach ($users->errors() as $message) {
                CLI::write($message, 'red');
            }
        }
    }

    /**
     * Automatically hashes the password when set.
     *
     * @see https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
     *
     * @param string $password
     */
    public function setPassword(string $password) {
        $config = config('Auth');

        if (
                (defined('PASSWORD_ARGON2I') && $config->hashAlgorithm == PASSWORD_ARGON2I) ||
                (defined('PASSWORD_ARGON2ID') && $config->hashAlgorithm == PASSWORD_ARGON2ID)
        ) {
            $hashOptions = [
                'memory_cost' => $config->hashMemoryCost,
                'time_cost' => $config->hashTimeCost,
                'threads' => $config->hashThreads
            ];
        } else {
            $hashOptions = [
                'cost' => $config->hashCost
            ];
        }

        $password_hash = password_hash(
                base64_encode(
                        hash('sha384', $password, true)
                ),
                $config->hashAlgorithm,
                $hashOptions
        );

        return $password_hash;
    }

}
