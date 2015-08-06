<?php

use JobQueue\Job;

class JobTest extends PHPUnit_Framework_TestCase
{
    private $job_name = 'SendEmail';

    private $job_data = ['subject' => 'Test email'];

    public function testCreateJob()
    {
        $job = new Job($this->job_name, $this->job_data);

        $this->assertNull($job->getId());
        $this->assertEquals($this->job_name, $job->getClassName());
        $this->assertEquals($this->job_data, $job->getArguments());
        $this->assertLessThanOrEqual(new DateTime(), $job->getCreatedAt());
    }

    public function testGetPayload()
    {
        $job = new Job($this->job_name, $this->job_data);

        $this->assertJson($job->getPayload());

        $this->assertEquals($this->job_name, json_decode($job->getPayload())->job);
        $this->assertEquals($this->job_data, json_decode($job->getPayload(), true)['data']);
        $this->assertEquals($job->getCreatedAt(), new DateTime(json_decode($job->getPayload())->created_at));
    }

    public function testCreateFromPayload()
    {
        $job = new Job($this->job_name, $this->job_data);

        $job2 = Job::createFromPayload($job->getPayload());

        $this->assertEquals($job, $job2);
    }
}