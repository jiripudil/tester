<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

namespace Tester\Runner\Output;

use Tester;
use Tester\Runner\Runner;


/**
 * TeamCity format printer (for use with PhpStorm, for instance)
 */
class TeamCityPrinter implements Tester\Runner\OutputHandler
{

	/** @var Runner */
	private $runner;

	/** @var resource */
	private $file;

	/** @var bool */
	private $started = FALSE;


	public function __construct(Runner $runner, $file = 'php://output')
	{
		$this->runner = $runner;
		$this->file = fopen($file, 'w');
	}


	public function begin()
	{
	}


	public function result($testName, $result, $message, Tester\Runner\Job $job = NULL)
	{
		if (!$this->started) {
			$this->startSuite();
		}

		$escapedName = $this->escape($testName);
		$escapedMessage = $this->escape($message);

		fwrite($this->file, "##teamcity[testStarted name='$escapedName']\n\n");

		if ($result === Runner::SKIPPED) {
			fwrite($this->file, "##teamcity[testIgnored name='$escapedName' message='$escapedMessage']\n\n");

		} elseif ($result === Runner::FAILED) {
			fwrite($this->file, "##teamcity[testFailed name='$escapedName' message='$escapedMessage']\n\n");
		}

		$time = $job !== NULL ? (int) round($job->getTime() * 1000) : 0;
		fwrite($this->file, "##teamcity[testFinished name='$escapedName' duration='$time']\n\n");
	}


	public function end()
	{
		fwrite($this->file, "##teamcity[testSuiteFinished name='Tests']\n\n");
	}


	private function startSuite()
	{
		fwrite($this->file, "##teamcity[testCount count='{$this->runner->getJobCount()}']\n\n");
		fwrite($this->file, "##teamcity[testSuiteStarted name='Tests']\n\n");
		$this->started = TRUE;
	}


	private function escape($value)
	{
		$value = str_replace('|', '||', $value);
		$value = str_replace("'", "|'", $value);
		$value = str_replace("\n", '|n', $value);
		$value = str_replace("\r", '|r', $value);
		$value = str_replace(']', '|]', $value);
		$value = str_replace('[', '|[', $value);

		return $value;
	}

}
