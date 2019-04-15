<?php

class CIDatabaseTestCase extends \CodeIgniter\Test\CIDatabaseTestCase
{
    /**
     * Should the db be refreshed before
     * each test?
     *
     * @var boolean
     */
    protected $refresh = true;

    /**
     * The name of the fixture used for all tests
     * within this test case.
     *
     * @var string
     */
    protected $seed = '';

    /**
     * The path to where we can find the migrations
     * and seeds directories. Allows overriding
     * the default application directories.
     *
     * @var string
     */
    protected $basePath = __DIR__ . '/../../src/Database/';

    /**
     * The namespace to help us find the migration classes.
     *
     * @var string
     */
    protected $namespace = 'Myth\Auth';
}
