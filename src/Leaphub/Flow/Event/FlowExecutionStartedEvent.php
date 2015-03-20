<?php

namespace Leaphub\Flow\Event;

use Leaphub\Flow\Model\FlowInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * The FlowExecutionStartedEvent is fired immediately before the execution of a flow starts.
 */
class FlowExecutionStartedEvent extends Event
{
    /**
     * @var FlowInterface $flow
     */
    private $flow;

    /**
     * @param FlowInterface $flow The flow which execution has started
     */
    public function __construct($flow)
    {
        $this->flow = $flow;
    }

    /**
     * @return FlowInterface
     */
    public function getFlow()
    {
        return $this->flow;
    }
} 