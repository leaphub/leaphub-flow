<?php

namespace Leaphub\Flow\Event;

/**
 * Contains the identifier of all flow related events.
 */
class FlowEvents
{
    /**
     * The "flow.flow_exec.started" event is fired immediately before the execution of a flow starts.
     *
     * The event listener receives an Leaphub\Flow\Event\FlowExecutionStartedEvent instance
     */
    const FLOW_EXECUTION_STARTED = 'flow.flow_exec.started';

    /**
     * The "flow.flow_exec.finished" event is fired after all jobs in a flow have successfully been executed.
     *
     * The event listener receives an Leaphub\Flow\Event\FlowExecutionFinishedEvent instance
     */
    const FLOW_EXECUTION_FINISHED = 'flow.flow_exec.finished';

    /**
     * The "flow.job_exec.started" event is fired immediately before a job of a flow is executed.
     *
     * The event listener receives an Leaphub\Flow\Event\JobExecutionStartedEvent instance
     */
    const JOB_EXECUTION_STARTED = 'flow.job_exec.started';

    /**
     * The "flow.job_exec.finished" event is fired after a job of a flow has successfully been executed.
     *
     * The event listener receives an Leaphub\Flow\Event\JobExecutionFinishedEvent instance
     */
    const JOB_EXECUTION_FINISHED = 'flow.job_exec.finished';
}