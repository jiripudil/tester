<?php

/**
 * @phpversion 5.4  Requires constant PHP_BINARY available since PHP 5.4.0
 */

use Tester\Assert;
use Tester\Environment;
use Tester\Runner\Output\TeamCityPrinter;


require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../../src/Runner/TestHandler.php';
require __DIR__ . '/../../src/Runner/Runner.php';
require __DIR__ . '/../../src/Runner/OutputHandler.php';
require __DIR__ . '/../../src/Runner/Output/TeamCityPrinter.php';


Environment::$useColors = FALSE;
$runner = new Tester\Runner\Runner(createInterpreter());
$printer = new TeamCityPrinter($runner);
$runner->paths[] = __DIR__ . '/cases/*.phptx';
$runner->outputHandlers[] = $printer;
ob_start();
$runner->run();
$output = ob_get_clean();

$expected = <<<TC
##teamcity[testCount count='3']

##teamcity[testSuiteStarted name='Tests']

##teamcity[testStarted name='RunnerOutput%ds%cases%ds%fail.phptx']

##teamcity[testFailed name='RunnerOutput%ds%cases%ds%fail.phptx' message='Failed: STOP|n|nin RunnerOutput%ds%cases%ds%fail.phptx(4) Tester\\Assert::fail(|'STOP|');']

##teamcity[testFinished name='RunnerOutput%ds%cases%ds%fail.phptx' duration='%f%']

##teamcity[testStarted name='RunnerOutput%ds%cases%ds%pass.phptx']

##teamcity[testFinished name='RunnerOutput%ds%cases%ds%pass.phptx' duration='%f%']

##teamcity[testStarted name='RunnerOutput%ds%cases%ds%skip.phptx']

##teamcity[testIgnored name='RunnerOutput%ds%cases%ds%skip.phptx' message='']

##teamcity[testFinished name='RunnerOutput%ds%cases%ds%skip.phptx' duration='%f%']

##teamcity[testSuiteFinished name='Tests']
TC;

Assert::match($expected, $output);
