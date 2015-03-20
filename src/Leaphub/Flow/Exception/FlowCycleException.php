<?php

namespace Leaphub\Flow\Exception;

use Exception;

/**
 * Exception intended to be thrown if a flow is not executable due to a cycle in the execution graph.
 */
class FlowCycleException extends \Exception
{
    /**
     * @var array $cycleTrace
     */
    private $cycleTrace;

    /**
     * @param array     $cycleTrace A trace of job ids creating the cycle
     * @param string    $message    [optional] The Exception message to throw
     * @param int       $code       [optional] The Exception code
     * @param Exception $previous   [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct(array $cycleTrace, $message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->cycleTrace = $cycleTrace;
    }

    /**
     * Returns a trace of job ids which created the cycle.
     *
     * @return array
     */
    public function getCycleTrace()
    {
        return $this->cycleTrace;
    }
}