<?php

namespace Leaphub\Flow;

use Leaphub\Flow\Exception\FlowCycleException;
use Leaphub\Flow\Exception\JobExecutionException;
use Leaphub\Flow\Model\FlowInterface;

/**
 * Defines a flow executor which provides operations on job flows.
 */
interface FlowExecutorInterface
{
    /**
     * Executes a given flow. Always performs a validation of the flow before executing it.
     *
     * @param FlowInterface $flow
     *
     * @throws JobExecutionException If something went wrong during the execution of a job in the flow.
     */
    public function executeFlow(FlowInterface $flow);

    /**
     * Determines and returns the job execution order of a given flow.
     *
     * @param FlowInterface $flow
     *
     * @return string[] An array of job identifier sorted in execution order
     */
    public function getExecutionOrder(FlowInterface $flow);
}