<?php

namespace App\Console\Commands;



use Illuminate\Console\GeneratorCommand;

class VerifyRuleAdd extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:rule_verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'new a rule name';

    protected $type = 'Rule';
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/VerifyRule.stub';
    }


    protected function getNameInput()
    {
        return trim($this->argument('name') . $this->type);
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Rules';
    }
}
