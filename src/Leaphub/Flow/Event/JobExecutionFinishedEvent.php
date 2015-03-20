<?php

namespace Leaphub\Flow\Event;

use Leaphub\Flow\Model\JobInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * The JobExecutionFinishedEvent is fired after the execution of a job in a flow has finished.
 */
class JobExecutionFinishedEvent extends Event
{
    /**
     * @var JobInterface $job
     */
    private $job;

    /**
     * @param JobInterface $job The job which execution has finished
     */
    public function __construct($job)
    {
        $this->job = $job;
    }

    /**
     * @return JobInterface
     */
    public function getJob()
    {
        return $this->job;
    }
} 