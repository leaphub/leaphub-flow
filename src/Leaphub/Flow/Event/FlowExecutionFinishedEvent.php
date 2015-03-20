<?php

namespace Leaphub\Flow\Event;

use Leaphub\Flow\Model\FlowInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * The FlowExecutionFinishedEvent is fired when the execution of a flow has finished.
 */
class FlowExecutionFinishedEvent extends Event
{
    /**
     * @var FlowInterface $flow
     */
    private $flow;

    /**
     * @param FlowInterface $flow The flow which execution has finished
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