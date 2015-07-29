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

	public function fetchNextJob($delay_time = 60)
	{
		$pheanstalk_job = $this->pheanstalk->reserveFromTube($this->queue_name, $delay_time);
		if (! $pheanstalk_job) {
			return false;
		}
		$payload = $pheanstalk_job->getData();

		$job = Job::createFromPayload($payload);
		$job->setId($pheanstalk_job->getId());

		return $job;
	}

	public function release(Job $job, $delay_time = 60)
	{
		$this->pheanstalk->release(new \Pheanstalk\Job($job->getId(), []), null, $delay_time);
	}

	public function bury(Job $job)
	{
		$this->pheanstalk->bury(new \Pheanstalk\Job($job->getId(), []));
	}

	public function delete(Job $job)
	{
		$this->pheanstalk->delete(new \Pheanstalk\Job($job->getId(), []));
	}

	public function countReserves(Job $job)
	{
		$stats = $this->pheanstalk->statsJob($job->getId());
		return $stats->reserves;
	}

	public function getPheanstalkInstance()
	{
		return $this->pheanstalk;
	}
}