<?php

use Myth\Auth\Models\UserModel;
use ModuleTests\Support\AuthTestCase;
use Myth\Auth\Authorization\GroupModel;

class UserModelTest extends AuthTestCase
{
    /**
     * @var UserModel
     */
    protected $users;

    public function setUp(): void
    {
        parent::setUp();

        $this->users = new UserModel();
    }

    public function testInsertBasics()
    {
        $data = [
            'username' => 'Joe Cool',
            'email' => 'jc@example.com',
            'password_hash' => 'cornedbeef',
        ];

        $userId = $this->users->insert($data);

        $this->seeInDatabase('users', [
            'id' => $userId,
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
        ]);
    }

    public function testInsertDefaultGroupNotFound()
    {
        $data = [
            'username' => 'Joe Cool',
            'email' => 'jc@example.com',
            'password_hash' => 'cornedbeef',
        ];

        $config = new \Myth\Auth\Config\Auth();
        $config->defaultGroup = 'unknown';
        \CodeIgniter\Config\Config::injectMock('Auth', $config);

        $userId = $this->users->insert($data);

        $this->seeInDatabase('users', [
            'id' => $userId,
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
        ]);

        $this->dontSeeInDatabase('auth_groups_users', [
            'user_id' => $userId
        ]);
    }

    public function testInsertDefaultGroupAddsGroup()
    {
        $data = [
            'username' => 'Joe Cool',
            'email' => 'jc@example.com',
            'password_hash' => 'cornedbeef',
        ];

        $group = $this->createGroup([
            'name' => 'guests',
            'description' => 'guests'
        ]);

        $userId = $this->users
            ->withGroup($group->name)
            ->insert($data);

        $this->seeInDatabase('users', [
            'id' => $userId,
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
        ]);

        $this->seeInDatabase('auth_groups_users', [
            'group_id' => $group->id,
            'user_id' => $userId
        ]);
    }
}
