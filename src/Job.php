<?php

namespace JobQueue;

use DateTime;

class Job
{
    private $class_name;

    private $arguments;

    private $created_at;

    private $id;

    public function __construct($class_name, $arguments)
    {
        $this->class_name = $class_name;
        $this->arguments = $arguments;
        $this->created_at = new DateTime();
    }

    public static function createFromPayload($payload)
    {
        $data = json_decode($payload, true);

        return new self($data['job'], $data['data']);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCreatedAt(DateTime $created_at)
    {
        $this->created_at = $created_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getClassName()
    {
        return $this->class_name;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getPayload()
    {
        return json_encode([
            'job' => $this->class_name,
            'created_at' => $this->created_at->format(DateTime::ISO8601),
            'data' => $this->arguments
        ]);
    }
}