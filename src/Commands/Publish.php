<?php namespace Myth\Auth\Commands;

use Config\Autoload;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;

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
    protected $options = [];

    /**
     * The path to Myth\Auth\src directory.
     *
     * @var string
     */
    protected $sourcePath;

    //--------------------------------------------------------------------

    /**
     * Displays the help for the spark cli script itself.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $this->determineSourcePath();

        // Migration
        if (CLI::prompt('Publish Migration?', ['y', 'n']) == 'y')
        {
            $this->publishMigration();
        }

        // Models
        if (CLI::prompt('Publish Models?', ['y', 'n']) == 'y')
        {
            $this->publishModels();
        }

        // Entities
        if (CLI::prompt('Publish Entities?', ['y', 'n']) == 'y')
        {
            $this->publishEntities();
        }

        // Controller
        if (CLI::prompt('Publish Controller?', ['y', 'n']) == 'y')
        {
            $this->publishController();
        }

        // Views
        if (CLI::prompt('Publish Views?', ['y', 'n']) == 'y')
        {
            $this->publishViews();
        }

        // Config
        if (CLI::prompt('Publish Config file?', ['y', 'n']) == 'y')
        {
            $this->publishConfig();
        }

        // Language
        if (CLI::prompt('Publish Language file?', ['y', 'n']) == 'y')
        {
            $this->publishLanguage();
        }
    }

    protected function publishModels()
    {
        $models = ['LoginModel', 'UserModel'];

        foreach ($models as $model)
        {
            $path = "{$this->sourcePath}/Models/{$model}.php";

            $content = file_get_contents($path);
            $content = $this->replaceNamespace($content, 'Myth\Auth\Models', 'Models');

            $this->writeFile("Models/{$model}.php", $content);
        }
    }

    protected function publishEntities()
    {
        $path = "{$this->sourcePath}/Entities/User.php";

        $content = file_get_contents($path);
        $content = $this->replaceNamespace($content, 'Myth\Auth\Entities', 'Entities');

        $this->writeFile("Entities/User.php", $content);
    }

    protected function publishController()
    {
        $path = "{$this->sourcePath}/Controllers/AuthController.php";

        $content = file_get_contents($path);
        $content = $this->replaceNamespace($content, 'Myth\Auth\Controllers', 'Controllers');

        $this->writeFile("Controllers/AuthController.php", $content);
    }

    protected function publishViews()
    {
        $map = directory_map($this->sourcePath . '/Views');
        $prefix = '';

        foreach ($map as $key => $view)
        {
            if (is_array($view))
            {
                $oldPrefix = $prefix;
                $prefix .= $key;

                foreach ($view as $file)
                {
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
        $path = "{$this->sourcePath}/Views/{$prefix}{$view}";

        $content = file_get_contents($path);
        $content = str_replace('Myth\Auth\Views', 'Auth', $content);

        $this->writeFile("Views/Auth/{$prefix}{$view}", $content);
    }

    protected function publishMigration()
    {
        $map = directory_map($this->sourcePath . '/Database/Migrations');

        foreach ($map as $file)
        {
            $content = file_get_contents("{$this->sourcePath}/Database/Migrations/{$file}");
            $content = $this->replaceNamespace($content, 'Myth\Auth\Database\Migrations', 'Database\Migrations');

            $this->writeFile("Database/Migrations/{$file}", $content);
        }

        CLI::write('  Remember to run `spark migrate:latest` to migrate the database.', 'blue');
    }

    protected function publishConfig()
    {
        $path = "{$this->sourcePath}/Config/Auth.php";

        $content = file_get_contents($path);
        $appNamespace = APP_NAMESPACE;
        $content = str_replace('namespace Myth\Auth\Config', "namespace {$appNamespace}\Config", $content);

        $this->writeFile("Config/Auth.php", $content);
    }

    protected function publishLanguage()
    {
        $path = "{$this->sourcePath}/Language/en/Auth.php";

        $content = file_get_contents($path);

        $this->writeFile("Language/en/Auth.php", $content);
    }

    //--------------------------------------------------------------------
    // Utilities
    //--------------------------------------------------------------------

    /**
     * Replaces the Myth\Auth namespace in the published
     * file with the applications current namespace.
     *
     * @param string $contents
     * @param string $originalNamespace
     * @param string $newNamespace
     *
     * @return string
     */
    protected function replaceNamespace(string $contents, string $originalNamespace, string $newNamespace): string
    {
        $appNamespace = APP_NAMESPACE;
        $originalNamespace = "namespace {$originalNamespace}";
        $newNamespace = "namespace {$appNamespace}\\{$newNamespace}";

        return str_replace($originalNamespace, $newNamespace, $contents);
    }

    /**
     * Determines the current source path from which all other files are located.
     */
    protected function determineSourcePath()
    {
        $this->sourcePath = realpath(__DIR__ . '/../');

        if ($this->sourcePath == '/' || empty($this->sourcePath))
        {
            CLI::error('Unable to determine the correct source directory. Bailing.');
            exit();
        }
    }

    /**
     * Write a file, catching any exceptions and showing a
     * nicely formatted error.
     *
     * @param string $path
     * @param string $content
     */
    protected function writeFile(string $path, string $content)
    {
        $config = new Autoload();
        $appPath = $config->psr4[APP_NAMESPACE];

        $directory = dirname($appPath . $path);

        if (! is_dir($directory))
        {
            mkdir($directory);
        }

        try
        {
            write_file($appPath . $path, $content);
        }
        catch (\Exception $e)
        {
            $this->showError($e);
            exit();
        }

        $path = str_replace($appPath, '', $path);

        CLI::write(CLI::color('  created: ', 'green') . $path);
    }
}
