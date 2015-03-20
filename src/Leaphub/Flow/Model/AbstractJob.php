<?php

namespace Leaphub\Flow\Model;

/**
 * Abstract base class for jobs in a flow.
 */
abstract class AbstractJob implements JobInterface
{
    /**
     * @var string $id
     */
    private $id;

    /**
     * @var JobInterface[] $beforeJobs
     */
    private $beforeJobs = array();

    /**
     * @var JobInterface[] $afterJobs
     */
    private $afterJobs = array();

    /**
     * @param string $id A unique identifier for the job
     */
    public function __construct($id)
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
    public function addBeforeJob(JobInterface $job)
    {
        $this->beforeJobs[] = $job;
    }

    /**
     * {@inheritdoc}
     */
    public function getBeforeJobs()
    {
        return $this->beforeJobs;
    }

    /**
     * {@inheritdoc}
     */
    public function addAfterJob(JobInterface $job)
    {
        $this->afterJobs[] = $job;
    }

    /**
     * {@inheritdoc}
     */
    public function getAfterJobs()
    {
        return $this->afterJobs;
    }

    /**
     * {@inheritdoc}
     */
    public abstract function execute();
}