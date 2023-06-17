<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateCrudService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make A CRUD Service';

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
        $path = app_path('Services') . '\\' . $name . 'Service';
        if (file_exists($path)) {
            $this->error('this path is already exists !!!!!!!!!!');
        } else {
            exec('mkdir ' . $path);
            file_put_contents($path . '\\' . $className . 'StoringService.php', $this->StoringService($className, $name));
            file_put_contents($path . '\\' . $className . 'UpdateingService.php', $this->UpdatingService($className . "UpdatingService", $name));
            $request = "$name\/{$className}StoringRequest";
            // Artisan::call("make:request $request");
            $this->info('The Service was successful!');
        }
    }

    function StoringService($className, $namespace)
    {
        $classContent = "<?php
        namespace App\Services\\$namespace;

        use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;
        // use App\Http\Requests\\$namespace\\{$className}Request;
        use App\Models\\$namespace\\{$className};
        class {$className}StoringService extends SingleRowStoringService
        {

            protected function getDefinitionCreatingFailingErrorMessage(): string
            {
                return 'Failed To Create The Given $className !';
            }

            protected function getDefinitionCreatingSuccessMessage(): string
            {
                return 'The $className Has Been Created Successfully !';
            }

            protected function getDefinitionModelClass(): string
            {
                return {$className}::class;
            }

            protected function getRequestClass(): string
            {
                return {$className}Request::class;
            }

        }

        ";
        return $classContent;
    }
    function UpdatingService($className, $namespace)
    {
        $classContent = "<?php
        namespace App\Services\\$namespace;

        use App\Services\WorkSector\WorkSectorUpdatingService;

        class $className extends WorkSectorUpdatingService
        {

            protected function getDefinitionUpdatingFailingErrorMessage(): string
            {
                return 'Failed To Update The Given $className !';
            }

            protected function getDefinitionUpdatingSuccessMessage(): string
            {
                return 'The $className Has Been Updated Successfully !';
            }

            protected function getRequestClass(): string
            {
                return {$className}Request::class;
            }
        }
        ";
        return $classContent;
    }
}
