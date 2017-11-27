<?php namespace Myth\Auth\Authentication;

use CodeIgniter\Model;
use Myth\Auth\Entities\User;
use Myth\Auth\Exceptions\AuthException;
use Myth\Auth\Exceptions\UserNotFoundException;

class AuthenticationBase
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Model
     */
    protected $userModel;

    /**
     * Logs a user into the system.
     * NOTE: does not perform validation. All validation should
     * be done prior to using the login method.
     *
     * @param \Myth\Auth\Entities\User $user
     * @param bool                     $remember
     */
    public function login(User $user, bool $remember = false)
    {
        $this->user = $user;
    }

    /**
     * Logs a user into the system by their ID.
     *
     * @param int  $id
     * @param bool $remember
     */
    public function loginByID(int $id, bool $remember = false)
    {
        $user = $this->$this->retrieveUser(['id' => $id]);

        if (empty($user))
        {
            throw UserNotFoundException::forUserID($id);
        }

        return $this->login($user, $remember);
    }

    /**
     * Logs a user out of the system.
     */
    public function logout()
    {
    }

    public function recordLoginAttempt()
    {
    }


    /**
     * Returns the User instance for the current logged in user.
     *
     * @return \Myth\Auth\Entities\User
     */
    public function user(): User
    {
        return $this->user;
    }

    /**
     * Grabs the current user from the database.
     *
     * @param array $wheres
     *
     * @return array|null|object
     */
    public function retrieveUser(array $wheres)
    {
        if (! $this->userModel instanceof Model)
        {
            throw AuthException::forInvalidModel('User');
        }

        $user = $this->userModel
            ->where($wheres)
            ->first();

        return $user;
    }

    //--------------------------------------------------------------------
    // Model Setters
    //--------------------------------------------------------------------

    /**
     * Sets the model that should be used to work with
     * user accounts.
     *
     * @param \CodeIgniter\Model $model
     *
     * @return $this
     */
    public function setUserModel(Model $model)
    {
        $this->userModel = $model;

        return $this;
    }

}

