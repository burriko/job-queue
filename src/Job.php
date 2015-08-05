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

    /**
     * Create Job instance from job payload
     *
     * @param string $payload JSON job payload
     * @return Job
     */
    public static function createFromPayload($payload)
    {
        $data = json_decode($payload, true);

        return new self($data['job'], $data['data']);
    }

    /**
     * Set Job id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get Job id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date job was created
     *
     * @param DateTime $created_at
     */
    public function setCreatedAt(DateTime $created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * Get date job was created
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Get name of job
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->class_name;
    }

    /**
     * Get job arguments
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Get job payload as JSON
     *
     * @return string
     */
    public function getPayload()
    {
        return json_encode([
            'job' => $this->class_name,
            'created_at' => $this->created_at->format(DateTime::ISO8601),
            'data' => $this->arguments
        ]);
    }
}