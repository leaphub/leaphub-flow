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
     * Adds a new job as post condition of this one which means the job passed as argument must be executed before this one.
     *
     * @param JobInterface $job
     */
    public function addBeforeJob(JobInterface $job);

    /**
     * Returns a list of jobs which have to be executed before this one.
     *
     * @return JobInterface[]
     */
    public function getBeforeJobs();

    /**
     * Adds a new job as post condition of this one which means the job passed as argument must be executed after this one.
     *
     * @param JobInterface $job
     */
    public function addAfterJob(JobInterface $job);

    /**
     * Returns a list of jobs which have to be executed after this one.
     *
     * @return JobInterface[]
     */
    public function getAfterJobs();

    /**
     * Executes a job.
     *
     * @throws JobExecutionException If something went wrong during the execution.
     */
    public function execute();
}