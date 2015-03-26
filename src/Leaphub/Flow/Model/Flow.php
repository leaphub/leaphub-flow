<?php

namespace Leaphub\Flow\Model;

use Leaphub\Flow\Exception\FlowException;

/**
 * A flow is a collection of jobs to be executed in a defined order
 */
class Flow implements FlowInterface
{
    /**
     * @var string $id
     */
    private $id;

    /**
     * @var JobInterface[] $jobs
     */
    private $jobs = array();

    /**
     * @param string $id A unique identifier for the flow
     */
    public function  __construct($id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function hasJob(JobInterface $job)
    {
        return $this->getJobById($job->getId()) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function addJob(JobInterface $job)
    {
        if (isset($this->jobs[$job->getId()])) {
            throw new FlowException(sprintf('The flow "%s" already contains a job with id "%s"', $this->getId(), $job->getId()));
        }

        $this->jobs[$job->getId()] = $job;
    }

    /**
     * {@inheritdoc}
     */
    public function getJobById($jobId)
    {
        if (isset($this->jobs[$jobId])) {
            return $this->jobs[$jobId];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * {@inheritdoc}
     */
    public function getJobCount()
    {
        return count($this->jobs);
    }
}