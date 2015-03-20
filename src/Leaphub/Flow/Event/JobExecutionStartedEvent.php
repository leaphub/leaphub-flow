<?php

namespace Leaphub\Flow\Event;

use Leaphub\Flow\Model\JobInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * The JobExecutionStartedEvent is fired immediately before a job in a flow is executed.
 */
class JobExecutionStartedEvent extends Event
{
    /**
     * @var JobInterface $job
     */
    private $job;

    /**
     * @param JobInterface $job The job which execution has started
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