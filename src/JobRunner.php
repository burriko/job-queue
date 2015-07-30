<?php

namespace JobQueue;

class JobRunner
{
    private $instantiator;

    public function __construct()
    {
        // Set a default instantiator
        $this->setInstantiator(function($class_name) { return new $class_name; });
    }

    public function setInstantiator($instantiator)
    {
        $this->instantiator = $instantiator;
    }

    public function runJob(Job $job)
    {
        $command = $this->instantiateCommand($job->getClassName());

        return call_user_func_array([$command, 'handle'], $job->getArguments());
    }

    private function instantiateCommand($class_name)
    {
        return call_user_func($this->instantiator, $class_name);
    }
}