<?php

namespace Leaphub\Flow\Model;

/**
 * Abstract base class for jobs in a flow.
 */
abstract class AbstractJob implements JobInterface
{

    /**
     * @var string $id
     */
    private $id;

    /**
     * @var FlowInterface $flow
     */
    private $flow;

    /**
     * @var JobInterface[] $preConditions
     */
    private $preConditions = array();

    /**
     * @var JobInterface[] $postConditions
     */
    private $postConditions = array();

    /**
     * @param string $id A unique identifier for the job
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getFlow()
    {
        return $this->flow;
    }

    /**
     * {@inheritdoc}
     */
    public function setFlow(FlowInterface $flow)
    {
        $this->flow = $flow;
    }

    /**
     * {@inheritdoc}
     */
    public function executeBefore(JobInterface $job)
    {
        $this->postConditions[] = $job;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostConditions()
    {
        return $this->postConditions;
    }

    /**
     * {@inheritdoc}
     */
    public function executeAfter(JobInterface $job)
    {
        $this->preConditions[] = $job;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreConditions()
    {
        return $this->preConditions;
    }

    /**
     * {@inheritdoc}
     */
    public abstract function execute();
}