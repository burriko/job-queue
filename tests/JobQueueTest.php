<?php

use JobQueue\BeanstalkQueue;
use JobQueue\Job;

class JobQueueTest extends PHPUnit_Framework_TestCase
{
	private $job_name = 'SendEmail';

	private $job_data = ['subject' => 'Test email'];

	public function testCreateJob()
	{
		$job = new Job($this->job_name, $this->job_data);

		$this->assertEquals($this->job_name, $job->getClassName());
		$this->assertEquals($this->job_data, $job->getArguments());
	}

	public function testPushJobToQueue()
	{
		$job = new Job($this->job_name, $this->job_data);

		$pheanstalk = $this->createPheanstalkMock();
		$pheanstalk->method('putInTube')->willReturn(123456);

		$queue = new BeanstalkQueue($pheanstalk, 'test');
		$queue->push($job);
	}

	public function testFetchNextJob()
	{
		$pheanstalk_job = $this->createPheanstalkJobMock();
		$pheanstalk_job->method('getData')->willReturn($this->createTestPayload());

		$pheanstalk = $this->createPheanstalkMock();
		$pheanstalk->method('reserveFromTube')->willReturn($pheanstalk_job);

		$queue = new BeanstalkQueue($pheanstalk, 'test');
		$job = $queue->fetchNextJob();

		$this->assertInstanceOf('JobQueue\Job', $job);
		$this->assertEquals($this->job_name, $job->getClassName());
		$this->assertEquals($this->job_data, $job->getArguments());
	}

	public function testDeleteJobFromQueue()
	{
		$pheanstalk = $this->createPheanstalkMock();
		$pheanstalk->method('delete')->willReturn($pheanstalk);

		$job = new Job($this->job_name, $this->job_data);
		$job->setId(123456);

		$queue = new BeanstalkQueue($pheanstalk, 'test');
		$queue->delete($job);
	}

	private function createPheanstalkMock()
	{
		return $this->getMockBuilder(Pheanstalk\PheanstalkInterface::class)->getMock();
	}

	private function createPheanstalkJobMock()
	{
		return $this->getMockBuilder(Pheanstalk\Job::class)->disableOriginalConstructor()->getMock();
	}

	private function createTestPayload()
	{
		return json_encode(['job' => $this->job_name, 'data' => $this->job_data]);
	}
}