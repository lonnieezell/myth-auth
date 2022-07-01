<?php

use CodeIgniter\Config\Factories;
use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Models\UserModel;
use Tests\Support\AuthTestCase;

/**
 * @internal
 */
final class UserModelTest extends AuthTestCase
{
    /**
     * @var UserModel
     */
    protected $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = new UserModel();
    }

    public function testInsertBasics()
    {
        $data = [
            'username'      => 'Joe Cool',
            'email'         => 'jc@example.com',
            'password_hash' => 'cornedbeef',
        ];

        $userId = $this->users->insert($data);

        $this->seeInDatabase('users', [
            'id'            => $userId,
            'username'      => $data['username'],
            'email'         => $data['email'],
            'password_hash' => $data['password_hash'],
        ]);
    }

    public function testInsertDefaultGroupNotFound()
    {
        $data = [
            'username'      => 'Joe Cool',
            'email'         => 'jc@example.com',
            'password_hash' => 'cornedbeef',
        ];

        $config                   = new AuthConfig();
        $config->defaultUserGroup = 'unknown';
        Factories::injectMock('config', 'Auth', $config);

        $userId = $this->users->insert($data);

        $this->seeInDatabase('users', [
            'id'            => $userId,
            'username'      => $data['username'],
            'email'         => $data['email'],
            'password_hash' => $data['password_hash'],
        ]);

        $this->dontSeeInDatabase('auth_groups_users', [
            'user_id' => $userId,
        ]);
    }

    public function testInsertDefaultGroupAddsGroup()
    {
        $data = [
            'username'      => 'Joe Cool',
            'email'         => 'jc@example.com',
            'password_hash' => 'cornedbeef',
        ];

        $group = $this->createGroup([
            'name'        => 'guests',
            'description' => 'guests',
        ]);

        $userId = $this->users
            ->withGroup($group->name)
            ->insert($data);

        $this->seeInDatabase('users', [
            'id'            => $userId,
            'username'      => $data['username'],
            'email'         => $data['email'],
            'password_hash' => $data['password_hash'],
        ]);

        $this->seeInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id'  => $userId,
        ]);
    }
}
