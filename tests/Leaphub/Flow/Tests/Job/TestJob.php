<?php

namespace Leaphub\Flow\Tests\Job;

use Leaphub\Flow\Model\AbstractJob;

/**
 * A simple job implementation which allows to test if a job has been executed or not and allows to determine the
 * execution order.
 */
class TestJob extends AbstractJob
{

    /**
     * @var ExecCounter $execCounter
     */
    private $execCounter;

    /**
     * @var int $execPosition
     */
    private $execPosition = -1;

    /**
     * @var bool $executed
     */
    private $executed = false;

    /**
     * @var array $callback
     */
    private $callback;

    /**
     * @param string      $id          A unique identifier for the job
     * @param ExecCounter $execCounter [optional] An execution counter object
     * @param \Closure    $callback    [optional] An optional callback, called when the job is executed
     */
    public function __construct($id, ExecCounter $execCounter = null, \Closure $callback = null)
    {
        parent::__construct($id);

        $this->execCounter = $execCounter;
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        // increment counter if specified
        if ($this->execCounter !== null) {
            $this->execCounter->increment();
            $this->execPosition = $this->execCounter->getCount();
        }

        // execute callback if specified
        if ($this->callback !== null) {
            call_user_func($this->callback);
        }

        $this->executed = true;
    }

    /**
     * Checks if the job has been executed or not.
     *
     * @return bool
     */
    public function hasBeenExecuted()
    {
        return $this->executed;
    }

    /**
     * Returns the position where the job was executed when processing a flow.
     * The position is -1 if the job was never executed.
     *
     * @return int
     */
    public function getExecPosition()
    {
        return $this->execPosition;
    }
}