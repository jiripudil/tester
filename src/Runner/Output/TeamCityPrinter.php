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

	/** @var array */
	private $suites = array();


	public function __construct(Runner $runner, $file = 'php://output')
	{
		$this->runner = $runner;
		$this->file = fopen($file, 'w');
	}


	public function begin()
	{
	}


	public function jobsProcessed($jobs, $jobCount)
	{
		foreach ($jobs as $job) {
			if (!isset($this->suites[$job->getFile()])) {
				$this->suites[$job->getFile()] = array(
					'cases' => 0,
					'started' => FALSE,
				);
			}

			$this->suites[$job->getFile()]['cases']++;
		}

		fwrite($this->file, "##teamcity[testCount count='{$jobCount}']\n\n");
	}


	public function result($testName, $fileName, $result, $message, Tester\Runner\Job $job = NULL)
	{
		list($suiteName, $singleTestName) = $this->parseTestName($testName, $job);
		$escapedSuiteName = $this->escape($suiteName);
		$escapedName = $this->escape($singleTestName);
		$escapedFileName = $this->escape($fileName);

		if (!isset($this->suites[$fileName]) || !$this->suites[$fileName]['started']) {
			fwrite($this->file, "##teamcity[testSuiteStarted name='$escapedSuiteName' locationHint='tester_file://$escapedFileName' flowId='$escapedFileName']\n\n");

			if (isset($this->suites[$fileName])) {
				$this->suites[$fileName]['started'] = TRUE;
			}
		}

		$escapedMessage = $this->escape($message);

		$locationHint = $suiteName === $singleTestName
			? 'tester_file://' . $fileName
			: 'tester_method://' . $fileName . '#' . $singleTestName;
		fwrite($this->file, "##teamcity[testStarted name='$escapedName' locationHint='{$this->escape($locationHint)}' flowId='$escapedFileName']\n\n");

		if ($result === Runner::SKIPPED) {
			fwrite($this->file, "##teamcity[testIgnored name='$escapedName' message='$escapedMessage' flowId='$escapedFileName']\n\n");

		} elseif ($result === Runner::FAILED) {
			fwrite($this->file, "##teamcity[testFailed name='$escapedName' message='$escapedMessage' flowId='$escapedFileName']\n\n");
		}

		$time = $job !== NULL ? (int) round($job->getTime() * 1000) : 0;
		fwrite($this->file, "##teamcity[testFinished name='$escapedName' duration='$time' flowId='$escapedFileName']\n\n");

		if (!isset($this->suites[$fileName]) || --$this->suites[$fileName]['cases'] < 1) {
			fwrite($this->file, "##teamcity[testSuiteFinished name='$escapedSuiteName' flowId='$escapedFileName']\n\n");
		}
	}


	public function end()
	{
	}


	private function parseTestName($testName, Tester\Runner\Job $job = NULL)
	{
		preg_match('~((.+)\s+\|\s+)?([^\[]+)(\[(.+)\])?~', $testName, $matches);
		$testName = trim($matches[2]);
		$fileName = trim($matches[3]);
		$suiteName = !empty($testName) ? $testName : $fileName;

		$singleTestName = $suiteName;
		if ($job !== NULL) {
			foreach ($job->getArguments() as $arg) {
				if (preg_match('~--method=([a-zA-Z0-9_]+)~', $arg, $matches)) {
					$singleTestName = $matches[1];
				}
			}
		}

		return array($suiteName, $singleTestName);
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
