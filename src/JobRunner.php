<?php

namespace JobQueue;

class JobRunner
{
    private $instantiator;

    private $namespace;

    public function __construct()
    {
        // Set a default instantiator
        $this->setInstantiator(function($class_name) { return new $class_name; });
    }

    public function setInstantiator($instantiator)
    {
        $this->instantiator = $instantiator;
    }

    public function setNamespace($namespace)
    {
        if (substr($namespace, -1) != '\\') {
            $namespace .= '\\';
        }
        $this->namespace = $namespace;
    }

    public function runJob(Job $job)
    {
        $command = $this->instantiateCommand($job->getClassName());

        return call_user_func_array([$command, 'handle'], $job->getArguments());
    }

    private function instantiateCommand($class_name)
    {
        if (! empty($this->namespace)) {
            $class_name = $this->namespace . $class_name;
        }
        return call_user_func($this->instantiator, $class_name);
    }
}