<?php

namespace Leaphub\Flow\Tests;

use Leaphub\Flow\Exception\FlowCycleException;
use Leaphub\Flow\Exception\JobExecutionException;
use Leaphub\Flow\FlowExecutor;
use Leaphub\Flow\Model\Flow;
use Leaphub\Flow\Tests\Job\ExecCounter;
use Leaphub\Flow\Tests\Job\TestJob;

/**
 * Tests the flow executor.
 */
class FlowExecutorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the execution of a simple job flow.
     */
    public function testSimpleExecution()
    {
        $counter = new ExecCounter();

        $flow = new Flow('test-flow');

        $job2 = new TestJob('test-job-2', $counter);
        $flow->addJob($job2);
        $job3 = new TestJob('test-job-3', $counter);
        $flow->addJob($job3);
        $job1 = new TestJob('test-job-1', $counter);
        $flow->addJob($job1);

        $job1->addAfterJob($job2);
        $job1->addBeforeJob($job3);

        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $executor = new FlowExecutor($eventDispatcher);

        $executionOrder = $executor->getExecutionOrder($flow);

        $this->assertEquals('test-job-2', $executionOrder[0]);
        $this->assertEquals('test-job-1', $executionOrder[1]);
        $this->assertEquals('test-job-3', $executionOrder[2]);

        $executor->executeFlow($flow);
        $this->assertEquals(1, $job2->getExecPosition());
        $this->assertEquals(2, $job1->getExecPosition());
        $this->assertEquals(3, $job3->getExecPosition());
    }

    /**
     * Test if the flow executor properly detects cycles in a job flow.
     */
    public function testCycleDetection()
    {
        $counter = new ExecCounter();

        $flow = new Flow('test-flow');
        $job1 = new TestJob('test-job-1', $counter);
        $flow->addJob($job1);
        $job2 = new TestJob('test-job-2', $counter);
        $flow->addJob($job2);
        $job3 = new TestJob('test-job-3', $counter);
        $flow->addJob($job3);
        $job4 = new TestJob('test-job-4', $counter);
        $flow->addJob($job4);

        $job1->addBeforeJob($job2);
        $job4->addAfterJob($job2);
        $job2->addAfterJob($job3);
        $job3->addAfterJob($job4);

        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $executor = new FlowExecutor($eventDispatcher);

        $cycleDetected = false;
        try {
            $executor->getExecutionOrder($flow);
        } catch (FlowCycleException $e) {
            $cycleDetected = true;

            $this->assertEquals(
                array('test-job-2', 'test-job-3', 'test-job-4', 'test-job-2'),
                $e->getCycleTrace(),
                'Cycle trace did not contain the expected order'
            );
        }

        $this->assertTrue($cycleDetected, 'Cycle was not detected');
    }

    /**
     * Test if the flow executor detects invalid flows.
     *
     * @expectedException Leaphub\Flow\Exception\FlowException
     */
    public function testInvalidFlowDetection()
    {
        $counter = new ExecCounter();

        $flow = new Flow('test-flow');
        $job1 = new TestJob('test-job-1', $counter);
        $flow->addJob($job1);
        $job2 = new TestJob('test-job-2', $counter);
        $flow->addJob($job2);
        $job3 = new TestJob('test-job-3', $counter);
        $flow->addJob($job3);

        $job2->addAfterJob($job3);
        $job3->addAfterJob($job2);

        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $executor = new FlowExecutor($eventDispatcher);
        $executor->getExecutionOrder($flow);
    }

    /**
     * Test if the flow executor detect cycle in one single job.
     *
     * @expectedException Leaphub\Flow\Exception\NoEntryPointException
     */
    public function testNoEntryPointDetection()
    {
        $counter = new ExecCounter();

        $flow = new Flow('test-flow');
        $job1 = new TestJob('test-job-1', $counter);
        $flow->addJob($job1);
        $job1->addAfterJob($job1);

        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $executor = new FlowExecutor($eventDispatcher);
        $executor->getExecutionOrder($flow);
    }

    /**
     * Test if the flow executor properly handles errors during job execution.
     */
    public function testJobExecutionException()
    {
        $counter = new ExecCounter();

        $flow = new Flow('test-flow');
        $job1 = new TestJob('test-job-1', $counter, function() {
            throw new \Exception('Test error during job execution');
        });
        $flow->addJob($job1);

        $eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
        $executor = new FlowExecutor($eventDispatcher);

        $executionFailed = false;
        try {
            $executor->executeFlow($flow);
        } catch (JobExecutionException $e) {
            $executionFailed = true;

            $this->assertEquals($job1, $e->getJob());
            $this->assertNotNull($e->getPrevious(), 'Cause of job execution error could not be determined');
            $this->assertTrue($e->getPrevious() instanceof \Exception);
        }

        $this->assertTrue($executionFailed, 'Job execution did not fail');
    }
}