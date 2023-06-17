<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateStatisticCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:statistic {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make A Static For Module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $className = $this->ask('what is Class name?');
        $path = app_path('Statistics') . '\\' . $name . 'Statistics';
        if (file_exists($path)) {
            $this->error('this path is already exists !!!!!!!!!!');
        } else {
            // exit($name.'\\'.$className.'StoringRequest');
            exec('mkdir ' . $path);
            file_put_contents($path . '\\' . $className . 'Boxes.php', $this->Boxes($className . "Boxes", $name));
            file_put_contents($path . '\\' . $className . 'Queries.php', $this->Queries($className . "Queries", $name));
            // Artisan::call("make:request $name\\".$className.'StoringRequest');
            $this->info('The Statistics was successful!');
        }
    }

    function Boxes($className, $namespace)
    {
        $context_view = '$context_view';
        $query = '$query';
        $classContent = "<?php
        namespace App\Statistics\\$namespace;
        use Illuminate\Support\Facades\DB;
        use App\Statistics\Interfaces\BoxesInterface;

        class $className implements BoxesInterface
        {
            public static function query($context_view){
                $query = DB::table('table_name');
                return $query;
            }
        }

        ";
        return $classContent;
    }
    function Queries($className, $namespace)
    {
        $context_view = '$context_view';
        $query = '$query';
        $classContent = "<?php
        namespace App\Statistics\\$namespace;
        use Illuminate\Support\Facades\DB;
        use App\Statistics\Interfaces\BoxesInterface;

        class $className implements BoxesInterface
        {
            public static function query($context_view){
                $query = DB::table('table_name');
                return $query;
            }
        }

        ";
        return $classContent;
    }
}
