<?php

namespace JobQueue;

interface Queue
{
	public function push(Job $job);

	public function fetchNextJob();

	public function delete(Job $job);
}