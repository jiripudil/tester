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

##teamcity[testSuiteStarted name='RunnerOutput%ds%cases%ds%fail.phptx' locationHint='tester_file://%a%RunnerOutput%ds%cases%ds%fail.phptx' flowId='%a%RunnerOutput%ds%cases%ds%fail.phptx']

##teamcity[testStarted name='RunnerOutput%ds%cases%ds%fail.phptx' locationHint='tester_file://%a%RunnerOutput%ds%cases%ds%fail.phptx' flowId='%a%RunnerOutput%ds%cases%ds%fail.phptx']

##teamcity[testFailed name='RunnerOutput%ds%cases%ds%fail.phptx' message='Failed: STOP|n|nin RunnerOutput%ds%cases%ds%fail.phptx(4) Tester\\Assert::fail(|'STOP|');' flowId='%a%RunnerOutput%ds%cases%ds%fail.phptx']

##teamcity[testFinished name='RunnerOutput%ds%cases%ds%fail.phptx' duration='%f%' flowId='%a%RunnerOutput%ds%cases%ds%fail.phptx']

##teamcity[testSuiteFinished name='RunnerOutput%ds%cases%ds%fail.phptx' flowId='%a%RunnerOutput%ds%cases%ds%fail.phptx']

##teamcity[testSuiteStarted name='RunnerOutput%ds%cases%ds%pass.phptx' locationHint='tester_file://%a%RunnerOutput%ds%cases%ds%pass.phptx' flowId='%a%RunnerOutput%ds%cases%ds%pass.phptx']

##teamcity[testStarted name='RunnerOutput%ds%cases%ds%pass.phptx' locationHint='tester_file://%a%RunnerOutput%ds%cases%ds%pass.phptx' flowId='%a%RunnerOutput%ds%cases%ds%pass.phptx']

##teamcity[testFinished name='RunnerOutput%ds%cases%ds%pass.phptx' duration='%f%' flowId='%a%RunnerOutput%ds%cases%ds%pass.phptx']

##teamcity[testSuiteFinished name='RunnerOutput%ds%cases%ds%pass.phptx' flowId='%a%RunnerOutput%ds%cases%ds%pass.phptx']

##teamcity[testSuiteStarted name='RunnerOutput%ds%cases%ds%skip.phptx' locationHint='tester_file://%a%RunnerOutput%ds%cases%ds%skip.phptx' flowId='%a%RunnerOutput%ds%cases%ds%skip.phptx']

##teamcity[testStarted name='RunnerOutput%ds%cases%ds%skip.phptx' locationHint='tester_file://%a%RunnerOutput%ds%cases%ds%skip.phptx' flowId='%a%RunnerOutput%ds%cases%ds%skip.phptx']

##teamcity[testIgnored name='RunnerOutput%ds%cases%ds%skip.phptx' message='' flowId='%a%RunnerOutput%ds%cases%ds%skip.phptx']

##teamcity[testFinished name='RunnerOutput%ds%cases%ds%skip.phptx' duration='%f%' flowId='%a%RunnerOutput%ds%cases%ds%skip.phptx']

##teamcity[testSuiteFinished name='RunnerOutput%ds%cases%ds%skip.phptx' flowId='%a%RunnerOutput%ds%cases%ds%skip.phptx']
TC;

Assert::match($expected, $output);
