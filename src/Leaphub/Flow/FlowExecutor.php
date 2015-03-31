<?php

namespace Leaphub\Flow;

use Leaphub\Flow\Event\FlowEvents;
use Leaphub\Flow\Event\FlowExecutionFinishedEvent;
use Leaphub\Flow\Event\FlowExecutionStartedEvent;
use Leaphub\Flow\Event\JobExecutionFinishedEvent;
use Leaphub\Flow\Event\JobExecutionStartedEvent;
use Leaphub\Flow\Exception\FlowCycleException;
use Leaphub\Flow\Exception\FlowException;
use Leaphub\Flow\Exception\JobExecutionException;
use Leaphub\Flow\Exception\NoEntryPointException;
use Leaphub\Flow\Model\FlowInterface;
use Leaphub\Flow\Model\JobInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * A basic flow executor implementation which executes the jobs sequentially based on topological sort.
 */
class FlowExecutor implements FlowExecutorInterface
{
    // States of a node during topological sort
    const SORT_VISITING = 1;
    const SORT_VISITED = 2;

    /**
     * @var EventDispatcherInterface $eventDispatcher
     */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function executeFlow(FlowInterface $flow)
    {
        $jobExecutionOrder = $this->getExecutionOrder($flow);

        $this->eventDispatcher->dispatch(FlowEvents::FLOW_EXECUTION_STARTED, new FlowExecutionStartedEvent($flow));

        foreach ($jobExecutionOrder as $jobId) {
            $this->executeJob($flow->getJobById($jobId));
        }

        $this->eventDispatcher->dispatch(FlowEvents::FLOW_EXECUTION_FINISHED, new FlowExecutionFinishedEvent($flow));
    }

    /**
     * Executes the given job.
     *
     * @param JobInterface $job
     *
     * @throws JobExecutionException If something went wrong during job execution
     */
    protected function executeJob(JobInterface $job)
    {
        $this->eventDispatcher->dispatch(FlowEvents::JOB_EXECUTION_STARTED, new JobExecutionStartedEvent($job));

        try {
            $job->execute();
        } catch (\Exception $e) {
            throw new JobExecutionException($job, sprintf('Execution of job "%s" failed', $job->getId()), 0, $e);
        }

        $this->eventDispatcher->dispatch(FlowEvents::JOB_EXECUTION_FINISHED, new JobExecutionFinishedEvent($job));
    }

    /**
     * {@inheritdoc}
     */
    public function getExecutionOrder(FlowInterface $flow)
    {
        $execOrder = array();

        $postConditions = $this->transformToPostConditions($flow);
        $entryJobs = $this->getEntryJobs($postConditions);

        if (count($entryJobs) < 1) {
            throw new NoEntryPointException('The flow has no entry point (job without pre condition)');
        }

        $nodeStates = array();

        foreach ($entryJobs as $entryJob) {
            $this->visit($entryJob, $postConditions, $nodeStates, $execOrder);
        }

        if ($flow->getJobCount() != count($nodeStates)) {
            throw new FlowException('Invalid flow, Could not determine an execution order as some nodes could not be reached');
        }

        return $execOrder;
    }

    /**
     * @param string   $jobId          The id of the job to visit
     * @param array[]  $postConditions The list of job post conditions
     * @param array    $nodeStates     A reference to the storage of the node visitation states
     * @param string[] $execOrder      A reference to the execution order result
     *
     * @throws FlowCycleException
     */
    private function visit($jobId, array $postConditions, array &$nodeStates, array &$execOrder)
    {
        // cycle detection
        if (isset($nodeStates[$jobId]) && $nodeStates[$jobId] === self::SORT_VISITING) {
            throw $this->createFlowCycleException($jobId, $execOrder);
        }

        // visit node
        $nodeStates[$jobId] = self::SORT_VISITING;

        $execOrder[] = $jobId;
        foreach ($postConditions[$jobId] as $postCondition) {
            $this->visit($postCondition, $postConditions, $nodeStates, $execOrder);
        }

        $nodeStates[$jobId] = self::SORT_VISITED;
    }

    /**
     * Creates a cycle trace and returns a new flow cycle exception based on it.
     *
     * @param string   $currentJobId The current job id closing the cycle
     * @param string[] $execOrder    The determined execution order until the cycle was detected
     *
     * @return FlowCycleException
     */
    private function createFlowCycleException($currentJobId, $execOrder)
    {
        $cycleTrace = $execOrder;
        foreach ($cycleTrace as $cycleJob) {
            if ($cycleJob === $currentJobId) {
                break;
            }
            array_shift($cycleTrace);
        }
        $cycleTrace[] = $currentJobId;
        $cycleTrace = array_reverse($cycleTrace);

        return new FlowCycleException($cycleTrace, sprintf('Cycle detected in flow [%s]', implode(' => ', $cycleTrace)));
    }

    /**
     * Determines a job which has no preconditions and can be executed immediately.
     *
     * @param array[] $flowPostConditions
     *
     * @return string[] A list of jobs without pre conditions to start parsing the flow
     */
    private function getEntryJobs(array $flowPostConditions)
    {
        $entryJobs = array();

        $conditionJobIds = array_keys($flowPostConditions);

        foreach ($conditionJobIds as $potentialJob) {
            foreach ($conditionJobIds as $jobId) {
                if (in_array($potentialJob, $flowPostConditions[$jobId])) {
                    break 2;
                }
            }

            $entryJobs[] = $potentialJob;
        }

        return $entryJobs;
    }

    /**
     * Transforms a job flow to an array of job ids and their post conditions.
     * For example would the return value for a job-1 which has to be executed before job-2 and job-3 be:
     *
     * 'job-1' => ['job-2', 'job-3']
     *
     * @param FlowInterface $flow
     *
     * @return string[]
     */
    private function transformToPostConditions(FlowInterface $flow)
    {
        $postCondition = array();

        foreach ($flow->getJobs() as $job) {
            if (!isset($postCondition[$job->getId()])) {
                $postCondition[$job->getId()] = array();
            }

            foreach ($job->getPostConditions() as $beforeJob) {
                $postCondition[$job->getId()][] = $beforeJob->getId();
            }

            foreach ($job->getPreConditions() as $afterJob) {
                if (!isset($postCondition[$afterJob->getId()])) {
                    $postCondition[$afterJob->getId()] = array();
                }
                if (!array_search($job->getId(), $postCondition[$afterJob->getId()])) {
                    $postCondition[$afterJob->getId()][] = $job->getId();
                }
            }
        }

        return $postCondition;
    }

}