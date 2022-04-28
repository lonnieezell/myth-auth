<?php

namespace Myth\Auth\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Autoload;

class Publish extends BaseCommand
{
    /**
     * The group the command is lumped under
     * when listing commands.
     *
     * @var string
     */
    protected $group = 'Auth';

    /**
     * The Command's name
     *
     * @var string
     */
    protected $name = 'auth:publish';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Publish selected Auth functionality into the current application.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'auth:publish';

    /**
     * the Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * the Command's Options
     *
     * @var array
     */
    protected $options = [
        '-f' => 'Force overwrite ALL existing files in destination',
    ];

    /**
     * The path to Myth\Auth\src directory.
     *
     * @var string
     */
    protected $sourcePath;

    /**
     * Whether the Views were published for local use.
     *
     * @var bool
     */
    protected $viewsPublished = false;

    /**
     * Whether the Entity were published for local use.
     *
     * @var bool
     */
    protected $entityPublished = false;

    //--------------------------------------------------------------------

    /**
     * Displays the help for the spark cli script itself.
     */
    public function run(array $params)
    {
        $this->determineSourcePath();

        // Migration
        if (CLI::prompt('Publish Migration?', ['y', 'n']) === 'y') {
            $this->publishMigration();
        }

        // Entities
        if (CLI::prompt('Publish Entities?', ['y', 'n']) === 'y') {
            $this->publishEntities();
            $this->entityPublished = true;
        }

        // Models
        if (CLI::prompt('Publish Models?', ['y', 'n']) === 'y') {
            $this->publishModels();
        }

        // Controller
        if (CLI::prompt('Publish Controller?', ['y', 'n']) === 'y') {
            $this->publishController();
        }

        // Views
        if (CLI::prompt('Publish Views?', ['y', 'n']) === 'y') {
            $this->publishViews();
            $this->viewsPublished = true;
        }

        // Filters
        if (CLI::prompt('Publish Filters?', ['y', 'n']) === 'y') {
            $this->publishFilters();
        }

        // Config
        if (CLI::prompt('Publish Config file?', ['y', 'n']) === 'y') {
            $this->publishConfig();
        }

        // Language
        if (CLI::prompt('Publish Language file?', ['y', 'n']) === 'y') {
            $this->publishLanguage();
        }
    }

    protected function publishModels()
    {
        $models = ['LoginModel', 'UserModel'];

        foreach ($models as $model) {
            $path = "{$this->sourcePath}/Models/{$model}.php";

            $content = file_get_contents($path);
            $content = $this->replaceNamespace($content, 'Myth\Auth\Models', 'Models');

            // Are we also Have we also published the Entity ?
            if ($model === 'UserModel' && $this->entityPublished) {
                $content = str_replace('User::class;', "'App\\Entities\\User';", $content);
            }

            $this->writeFile("Models/{$model}.php", $content);
        }
    }

    protected function publishEntities()
    {
        $path = "{$this->sourcePath}/Entities/User.php";

        $content = file_get_contents($path);
        $content = $this->replaceNamespace($content, 'Myth\Auth\Entities', 'Entities');

        $content = str_replace("use CodeIgniter\\Entity;\n", '', $content);
        $content = str_replace("use Myth\\Auth\\Authorization\\GroupModel;\n", '', $content);
        $content = str_replace("use Myth\\Auth\\Authorization\\PermissionModel;\n", '', $content);

        $content = str_replace('extends Entity', 'extends \\Myth\\Auth\\Entities\\User', $content);

        $this->writeFile('Entities/User.php', $content);
    }

    protected function publishController()
    {
        $path = "{$this->sourcePath}/Controllers/AuthController.php";

        $content = file_get_contents($path);
        $content = $this->replaceNamespace($content, 'Myth\Auth\Controllers', 'Controllers');

        $this->writeFile('Controllers/AuthController.php', $content);
    }

    protected function publishViews()
    {
        $map    = directory_map($this->sourcePath . '/Views');
        $prefix = '';

        foreach ($map as $key => $view) {
            if (is_array($view)) {
                $oldPrefix = $prefix;
                $prefix .= $key;

                foreach ($view as $file) {
                    $this->publishView($file, $prefix);
                }

                $prefix = $oldPrefix;

                continue;
            }

            $this->publishView($view, $prefix);
        }
    }

    protected function publishView($view, string $prefix = '')
    {
        $path      = "{$this->sourcePath}/Views/{$prefix}{$view}";
        $namespace = defined('APP_NAMESPACE') ? APP_NAMESPACE : 'App';

        $content = file_get_contents($path);
        $content = str_replace('Myth\Auth\Views', $namespace . '\Views\Auth', $content);

        $this->writeFile("Views/Auth/{$prefix}{$view}", $content);
    }

    protected function publishFilters()
    {
        $filters = ['LoginFilter', 'PermissionFilter', 'RoleFilter'];

        foreach ($filters as $filter) {
            $path = "{$this->sourcePath}/Filters/{$filter}.php";

            $content = file_get_contents($path);
            $content = $this->replaceNamespace($content, 'Myth\Auth\Filters', 'Filters');

            $this->writeFile("Filters/{$filter}.php", $content);
        }
    }

    protected function publishMigration()
    {
        $map = directory_map($this->sourcePath . '/Database/Migrations');

        foreach ($map as $file) {
            $content = file_get_contents("{$this->sourcePath}/Database/Migrations/{$file}");
            $content = $this->replaceNamespace($content, 'Myth\Auth\Database\Migrations', 'Database\Migrations');

            $this->writeFile("Database/Migrations/{$file}", $content);
        }

        CLI::write('  Remember to run `spark migrate -all` to migrate the database.', 'blue');
    }

    protected function publishConfig()
    {
        $path = "{$this->sourcePath}/Config/Auth.php";

        $content = file_get_contents($path);
        $content = str_replace('namespace Myth\Auth\Config', 'namespace Config', $content);
        $content = str_replace("use CodeIgniter\\Config\\BaseConfig;\n", '', $content);
        $content = str_replace('extends BaseConfig', 'extends \\Myth\\Auth\\Config\\Auth', $content);

        // are we also changing the views?
        if ($this->viewsPublished) {
            $namespace = defined('APP_NAMESPACE') ? APP_NAMESPACE : 'App';
            $content   = str_replace('Myth\Auth\Views', $namespace . '\Views\Auth', $content);
        }

        $this->writeFile('Config/Auth.php', $content);
    }

    protected function publishLanguage()
    {
        $path = "{$this->sourcePath}/Language/en/Auth.php";

        $content = file_get_contents($path);

        $this->writeFile('Language/en/Auth.php', $content);
    }

    //--------------------------------------------------------------------
    // Utilities
    //--------------------------------------------------------------------

    /**
     * Replaces the Myth\Auth namespace in the published
     * file with the applications current namespace.
     */
    protected function replaceNamespace(string $contents, string $originalNamespace, string $newNamespace): string
    {
        $appNamespace      = APP_NAMESPACE;
        $originalNamespace = "namespace {$originalNamespace}";
        $newNamespace      = "namespace {$appNamespace}\\{$newNamespace}";

        return str_replace($originalNamespace, $newNamespace, $contents);
    }

    /**
     * Determines the current source path from which all other files are located.
     */
    protected function determineSourcePath()
    {
        $this->sourcePath = realpath(__DIR__ . '/../');

        if ($this->sourcePath === '/' || empty($this->sourcePath)) {
            CLI::error('Unable to determine the correct source directory. Bailing.');

            exit();
        }
    }

    /**
     * Write a file, catching any exceptions and showing a
     * nicely formatted error.
     */
    protected function writeFile(string $path, string $content)
    {
        $config  = new Autoload();
        $appPath = $config->psr4[APP_NAMESPACE];

        $filename  = $appPath . $path;
        $directory = dirname($filename);

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (file_exists($filename)) {
            $overwrite = (bool) CLI::getOption('f');

            if (! $overwrite && CLI::prompt("  File '{$path}' already exists in destination. Overwrite?", ['n', 'y']) === 'n') {
                CLI::error("  Skipped {$path}. If you wish to overwrite, please use the '-f' option or reply 'y' to the prompt.");

                return;
            }
        }

        if (write_file($filename, $content)) {
            CLI::write(CLI::color('  Created: ', 'green') . $path);
        } else {
            CLI::error("  Error creating {$path}.");
        }
    }
}
