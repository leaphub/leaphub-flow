<?php

namespace Leaphub\Flow\Exception;

use Leaphub\Flow\Model\JobInterface;

/**
 * An exception thrown by a job if something went wrong during its the execution.
 */
class JobExecutionException extends \Exception
{
    /**
     * @var JobInterface $job
     */
    private $job;

    /**
     * @param JobInterface $job      The job which caused the exception
     * @param string       $message  [optional] The Exception message to throw.
     * @param int          $code     [optional] The Exception code.
     * @param \Exception   $previous [optional] The exception which caused the job to fail
     */
    public function __construct(JobInterface $job, $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->job = $job;
    }

    /**
     * The job which threw the exception.
     *
     * @return JobInterface
     */
    public function getJob()
    {
        return $this->job;
    }
} 