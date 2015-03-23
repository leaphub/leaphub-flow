<?php

namespace Leaphub\Flow\Model;

use Leaphub\Flow\Exception\JobExecutionException;

/**
 * Represents a single job in a flow.
 */
interface JobInterface
{
    /**
     * Returns the unique identifier of the job.
     *
     * @return string
     */
    public function getId();

    /**
     * Adds a new job as post condition of this one. This means the job passed as argument must be executed after this one.
     *
     * @param JobInterface $job
     */
    public function executeBefore(JobInterface $job);

    /**
     * Returns a list of jobs which have to be executed after this one.
     *
     * @return JobInterface[]
     */
    public function getPostConditions();

    /**
     * Adds a new job as pre condition of this one. This means the job passed as argument must be executed before this one.
     *
     * @param JobInterface $job
     */
    public function executeAfter(JobInterface $job);

    /**
     * Returns a list of jobs which have to be executed before this one.
     *
     * @return JobInterface[]
     */
    public function getPreConditions();

    /**
     * Executes a job.
     *
     * @throws JobExecutionException If something went wrong during the execution.
     */
    public function execute();
}