<?php

namespace JobQueue;

interface Queue
{
    public function push(Job $job);

    public function fetchNextJob();

    public function release(Job $job, $delay_time);

    public function bury(Job $job);

    public function delete(Job $job);

    public function countReserves(Job $job);

    public function getPheanstalkInstance();
}