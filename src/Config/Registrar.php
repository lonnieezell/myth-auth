<?php namespace Myth\Auth\Config;

/**
 * Helper class that will register our bulk plugins
 * and filters with the View Parser class.
 *
 * Called automatically by Config\View as long as
 * this file is setup as a Registrar:
 *
 *      protected $registrars = [
 *          \Myth\Template\Registrar::class
 *      ];
 */
class Registrar
{
    public static function View()
    {
        return [
            'plugins' => [
                'logged_in' => [ function ($str, array $params = []) { return service('authentication')->check() ? $str : ''; } ],
                'logged_out' => [ function ($str, array $params = []) { return ! service('authentication')->check() ? $str : ''; } ],
            ]
        ];
    }
}
