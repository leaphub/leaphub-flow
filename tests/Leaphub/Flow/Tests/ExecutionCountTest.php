<?php

namespace Leaphub\Flow\Tests;

use Leaphub\Flow\Tests\Job\ExecCounter;
use Leaphub\Flow\Tests\Job\TestJob;

class ExecutionCountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the execution position counting of the test job.
     */
    public function testExecutionCounter()
    {
        $counter = new ExecCounter();
        $job1 = new TestJob('test-job-1', $counter);
        $job2 = new TestJob('test-job-2', $counter);
        $job3 = new TestJob('test-job-3', $counter);

        $job2->execute();
        $this->assertTrue($job2->hasBeenExecuted());
        $this->assertEquals(1, $job2->getExecPosition());
        $job1->execute();
        $this->assertTrue($job1->hasBeenExecuted());
        $this->assertEquals(2, $job1->getExecPosition());
        $job3->execute();
        $this->assertTrue($job3->hasBeenExecuted());
        $this->assertEquals(3, $job3->getExecPosition());
    }
} 