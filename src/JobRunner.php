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

    /**
     * Executes the job
     *
     * @param  Job $job
     * @return boolean Returns false if the job failed
     */
    public function runJob(Job $job)
    {
        try {

            $command = $this->instantiateCommand($job->getClassName());

            $result = call_user_func_array([$command, 'handle'], $job->getArguments());

        } catch (\Exception $e) {

            $result = false;

        }

        return $result;
    }

    private function instantiateCommand($class_name)
    {
        if (!empty($this->namespace)) {
            $class_name = $this->namespace . $class_name;
        }
        return call_user_func($this->instantiator, $class_name);
    }
}