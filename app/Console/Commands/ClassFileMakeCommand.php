<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11/011
 * Time: 17:57
 */

namespace App\Console\Commands;




use Illuminate\Console\GeneratorCommand;

class ClassFileMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:simpleness';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new simpleness file class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'simpleness';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/FileClass.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

}