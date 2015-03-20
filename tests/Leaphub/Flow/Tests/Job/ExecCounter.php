<?php

namespace Leaphub\Flow\Tests\Job;

/**
 * An object which holds a counter to determine the execution position of a job.
 */
class ExecCounter
{
    /**
     * @var int $counter
     */
    private $counter = 0;

    /**
     * Increments the counter by 1.
     */
    public function increment()
    {
        $this->counter++;
    }

    /**
     * Returns the current value of the counter.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->counter;
    }
} 