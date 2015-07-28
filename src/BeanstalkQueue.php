<?php

namespace JobQueue;

use Pheanstalk\PheanstalkInterface;

class BeanstalkQueue implements Queue
{
	private $pheanstalk;

	private $queue_name;

	public function __construct(PheanstalkInterface $pheanstalk, $queue_name)
	{
		$this->pheanstalk = $pheanstalk;
		$this->queue_name = $queue_name;
	}

	public function push(Job $job)
	{
		$job_id = $this->pheanstalk->putInTube($this->queue_name, $job->getPayload());

		$job->setId($job_id);

		return $job;
	}

	public function fetchNextJob()
	{
		$pheanstalk_job = $this->pheanstalk->reserveFromTube($this->queue_name);
		$payload = $pheanstalk_job->getData();

		$job = Job::createFromPayload($payload);
		$job->setId($pheanstalk_job->getId());

		return $job;
	}

	public function delete(Job $job)
	{
		$this->pheanstalk->delete(new \Pheanstalk\Job($job->getId(), []));
	}
}