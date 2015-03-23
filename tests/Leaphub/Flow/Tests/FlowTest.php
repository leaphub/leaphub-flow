<?php

namespace Leaphub\Flow\Tests;

use Leaphub\Flow\Model\Flow;
use Leaphub\Flow\Model\JobInterface;
use Leaphub\Flow\Tests\Job\TestJob;

/**
 * Test the flow model
 */
class FlowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test if the flow properly forbids duplicate job identifier.
     *
     * @expectedException Leaphub\Flow\Exception\FlowException
     */
    public function testUniqueJob()
    {
        $flow = new Flow('test-flow');

        $job1 = new TestJob('duplucate-id', null);
        $job2 = new TestJob('duplucate-id', null);

        $flow->addJob($job1);
        $flow->addJob($job2);
    }

    /**
     * Tests if the collection of jobs in a flow is handled properly.
     */
    public function testJobCollection()
    {
        $flow = new Flow('test-flow');

        $job1 = new TestJob('test-job-1', null);
        $flow->addJob($job1);
        $job2 = new TestJob('test-job-2', null);
        $flow->addJob($job2);
        $job3 = new TestJob('test-job-3', null);
        $flow->addJob($job3);

        $this->assertEquals(3, $flow->getJobCount());
        $this->assertEquals(3, count($flow->getJobs()));

        foreach ($flow->getJobs() as $job) {
            $this->assertTrue($job instanceof JobInterface);
        }
    }

    /**
     * Tests if a job can be retrieved based on its id.
     */
    public function testRetrieveJob()
    {
        $flow = new Flow('test-flow');

        $job1 = new TestJob('test-job-1', null);
        $flow->addJob($job1);

        $this->assertEquals(null, $flow->getJobById('non-existent-job'));
        $this->assertEquals($job1, $flow->getJobById('test-job-1'));
    }

    public function testHasJob_JobShouldExist()
    {
        $flow = new Flow('test-flow');

        $job1 = new TestJob('test-job-1', null);
        $flow->addJob($job1);

        $job2 = new TestJob('test-job-2', null);
        $flow->addJob($job2);

        $this->assertTrue($flow->hasJob($job1));
    }

    public function testHasJob_JobShouldNotExist()
    {
        $flow = new Flow('test-flow');

        $job1 = new TestJob('test-job-1', null);
        $job2 = new TestJob('test-job-2', null);
        $flow->addJob($job2);

        $this->assertFalse($flow->hasJob($job1));
    }
}