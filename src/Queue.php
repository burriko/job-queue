<?php

namespace JobQueue;

interface Queue
{
    /**
     * Add a job to the queue
     *
     * @param  Job $job
     * @return Job
     */
    public function push(Job $job);

    /**
     * Fetch next job from the queue
     *
     * @return Job
     */
    public function fetchNextJob();

    /**
     * Release a job back to the queue
     *
     * @param Job $job
     * @param int $delay_time
     */
    public function release(Job $job, $delay_time);

    /**
     * Bury a job so that it cannot be fetched
     *
     * @param Job $job
     */
    public function bury(Job $job);

    /**
     * Delete job from queue
     *
     * @param Job $job
     */
    public function delete(Job $job);

    /**
     * Number of times job has been fetched
     *
     * @param Job $job
     * @return int
     */
    public function countReserves(Job $job);
}