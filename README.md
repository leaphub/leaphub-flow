# LeapHub Flow Engine 

[![Build Status](https://travis-ci.org/leaphub/leaphub-flow.svg?branch=master)](https://travis-ci.org/leaphub/leaphub-flow)

LeapHub flow allows you to define and execute job flows by specifying an arbitrary amount of jobs and their pre and post 
conditions.

## Installation



## Usage

1. Create the jobs to be executed



2. Instantiate them and specify pre and post conditions



3. Execute the flow



## Events

The library provides a number of events which allow you to hook into the flow execution process. The following events 
are triggered during the execution of a flow:

* `flow.flow_exec.started`: Immediately before a flow is executed.
* `flow.flow_exec.finished`: After all job of a flow have successfully been executed.
* `flow.job_exec.started`: Immediately before a job in a flow is executed.
* `flow.job_exec.finished`: After a job in a flow has successfully been executed.

For more information on the flow events see `Leaphub\Flow\Event\FlowEvents`.

## Running the tests

If the dev-dependencies are installed via composer, the test suite can be executed using:

    bin/phpunit -c phpunit.xml.dist --coverage-html ./coverage

## Contributing

1. Fork the repository
2. Create a branch for your contribution e.g. my-awesome-feature
3. Mage your changes (Follow the git commenting guidelines and code style)
4. Run the tests to ensure everything works fine
5. Crate a pull request


