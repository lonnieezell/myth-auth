<?php namespace Myth\Auth\Models;

use CodeIgniter\Model;
use Myth\Auth\Entities\User;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType = User::class;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'email', 'username', 'name', 'password_hash', 'reset_hash', 'reset_start_time', 'activate_hash',
	    'status', 'status_message', 'active', 'force_pass_reset', 'deleted'
    ];

    protected $useTimestamps = true;

    protected $validationRules    = [
    	'email'             => 'required|valid_email|is_unique[users.email]',
	    'username'          => 'required|alpha_numeric_space|min_length[3]',
	    'name'              => 'alpha_numeric_space',
	    'password_hash'     => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}
