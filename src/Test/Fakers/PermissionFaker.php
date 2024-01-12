<?php

namespace Myth\Auth\Test\Fakers;

use Faker\Generator;
use Myth\Auth\Authorization\PermissionModel;

class PermissionFaker extends PermissionModel
{
    /**
     * Faked data for Fabricator.
     */
    public function fake(Generator &$faker): array
    {
        return [
            'name'        => $faker->word(),
            'description' => $faker->sentence(),
        ];
    }
}
