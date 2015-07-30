<?php

namespace JobQueue;

class QueueWorker
{
    private $queue;

    private $runner;

    public function __construct(Queue $queue, JobRunner $runner)
    {
        $this->queue = $queue;
        $this->runner = $runner;
    }

    public function processQueue()
    {
        do {
            $this->processNextJob();
        } while (true);
    }

    public function processNextJob()
    {
        if ($job = $this->queue->fetchNextJob()) {
            $this->executeJob($job);
        }
    }

    private function executeJob($job)
    {
        try {

            $this->runner->runJob($job);

        } catch (Exception $e) {
            if ($this->queue->countReserves($job) < 3) {
                $this->queue->release($job);
            } else {
                $this->queue->bury($job);
            }
        }
    }
}