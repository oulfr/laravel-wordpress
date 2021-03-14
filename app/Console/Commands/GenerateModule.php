<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {class}';

    /**
     * The description.
     *
     * @var string
     */
    protected $description = 'Create all classes (model, factory, transformer, policy, controller, test)';


    /**
     * Override handle method
     * @return false
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $name = $this->argument('class');
        $this->info($this->description);
        $this->call('make:model', ['name' => $name]);
        $this->call('make:controller', ['name' => $name . 'Controller', '--resource' => '--resource']);
        $this->call('make:policy', ['name' => $name . 'Policy', '--model' => $name]);
        $this->call('make:transformer', ['name' => $name . 'Transformer', '--model' => 'App\Models\\' . $name]);
        $this->call('make:test', ['name' => $name . 'Test']);
    }
}
