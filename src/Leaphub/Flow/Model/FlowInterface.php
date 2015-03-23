<?php

namespace Leaphub\Flow\Model;

use Leaphub\Flow\Exception\FlowException;

/**
 * A flow is an executable collection of jobs.
 */
interface FlowInterface
{
    /**
     * Returns the unique identifier of the flow.
     *
     * @return string
     */
    public function getId();

    /**
     * Adds a job to the flow.
     *
     * @param JobInterface $job
     *
     * @throws FlowException If a job with the same ID already exists in the flow
     */
    public function addJob(JobInterface $job);

    /**
     * Fetches a job for the given job ID.
     *
     * @param string $id
     *
     * @return JobInterface The job object or null if no job with the given id was found.
     */
    public function getJobById($id);

    /**
     * Return all jobs of the flow.
     *
     * @return JobInterface[]
     */
    public function getJobs();

    /**
     * Return the amount of jobs the flow contains
     *
     * @return int
     */
    public function getJobCount();

    /**
     * Checks if the flow contains the given job.
     *
     * @param JobInterface $job
     *
     * @return bool
     */
    public function hasJob(JobInterface $job);
} 