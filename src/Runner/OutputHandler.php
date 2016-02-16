<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

namespace Tester\Runner;

use Tester;


/**
 * Runner output.
 */
interface OutputHandler
{

	function begin();

	function jobsProcessed($jobs, $jobCount);

	function result($testName, $fileName, $result, $message, Job $job = NULL);

	function end();

}
