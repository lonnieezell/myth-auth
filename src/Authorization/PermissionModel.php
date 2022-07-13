<?php

namespace Myth\Auth\Authorization;

use Myth\Auth\Models\PermissionModel as BaseModel;

/**
 * @deprecated 1.2.0 Use Myth\Auth\Models\PermissionModel instead
 */
class PermissionModel extends BaseModel
{
    protected $returnType = 'array';
}
