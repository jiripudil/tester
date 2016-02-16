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
$runner->paths[] = __DIR__ . '/tcCases/*.phptx';
$runner->outputHandlers[] = $printer;
ob_start();
$runner->run();
$output = ob_get_clean();

$expected = <<<TC
##teamcity[testSuiteStarted name='Skipped test' locationHint='tester_file://%a%RunnerOutput%ds%tcCases%ds%skippedTest.phptx' flowId='%a%RunnerOutput%ds%tcCases%ds%skippedTest.phptx']

##teamcity[testStarted name='Skipped test' locationHint='tester_file://%a%RunnerOutput%ds%tcCases%ds%skippedTest.phptx' flowId='%a%RunnerOutput%ds%tcCases%ds%skippedTest.phptx']

##teamcity[testIgnored name='Skipped test' message='' flowId='%a%RunnerOutput%ds%tcCases%ds%skippedTest.phptx']

##teamcity[testFinished name='Skipped test' duration='%f%' flowId='%a%RunnerOutput%ds%tcCases%ds%skippedTest.phptx']

##teamcity[testSuiteFinished name='Skipped test' flowId='%a%RunnerOutput%ds%tcCases%ds%skippedTest.phptx']

##teamcity[testCount count='6']

##teamcity[testSuiteStarted name='Named test' locationHint='tester_file://%a%RunnerOutput%ds%tcCases%ds%namedTest.phptx' flowId='%a%RunnerOutput%ds%tcCases%ds%namedTest.phptx']

##teamcity[testStarted name='Named test' locationHint='tester_file://%a%RunnerOutput%ds%tcCases%ds%namedTest.phptx' flowId='%a%RunnerOutput%ds%tcCases%ds%namedTest.phptx']

##teamcity[testFinished name='Named test' duration='%f%' flowId='%a%RunnerOutput%ds%tcCases%ds%namedTest.phptx']

##teamcity[testSuiteFinished name='Named test' flowId='%a%RunnerOutput%ds%tcCases%ds%namedTest.phptx']

##teamcity[testSuiteStarted name='Named TestCase' locationHint='tester_file://%a%RunnerOutput%ds%tcCases%ds%namedTestCase.phptx' flowId='%a%RunnerOutput%ds%tcCases%ds%namedTestCase.phptx']

##teamcity[testStarted name='testNamedTest' locationHint='tester_method://%a%RunnerOutput%ds%tcCases%ds%namedTestCase.phptx#testNamedTest' flowId='%a%RunnerOutput%ds%tcCases%ds%namedTestCase.phptx']

##teamcity[testFinished name='testNamedTest' duration='%f%' flowId='%a%RunnerOutput%ds%tcCases%ds%namedTestCase.phptx']

##teamcity[testSuiteFinished name='Named TestCase' flowId='%a%RunnerOutput%ds%tcCases%ds%namedTestCase.phptx']

##teamcity[testSuiteStarted name='RunnerOutput%ds%tcCases%ds%testCase.phptx' locationHint='tester_file://%a%RunnerOutput%ds%tcCases%ds%testCase.phptx' flowId='%a%RunnerOutput%ds%tcCases%ds%testCase.phptx']

##teamcity[testStarted name='testPass' locationHint='tester_method://%a%RunnerOutput%ds%tcCases%ds%testCase.phptx#testPass' flowId='%a%RunnerOutput%ds%tcCases%ds%testCase.phptx']

##teamcity[testFinished name='testPass' duration='%f%' flowId='%a%RunnerOutput%ds%tcCases%ds%testCase.phptx']

##teamcity[testStarted name='testFail' locationHint='tester_method://%a%RunnerOutput%ds%tcCases%ds%testCase.phptx#testFail' flowId='%a%RunnerOutput%ds%tcCases%ds%testCase.phptx']

##teamcity[testFailed name='testFail' message='Failed: STOP in testFail()|n|nin RunnerOutput/tcCases/testCase.phptx(21) Tester\Assert::fail(|'STOP|');|nin |[internal function|]UnnamedTestCase->testFail()|nin src/Framework/TestCase.php(152) call_user_func_array()|nin src/Framework/TestCase.php(58) Tester\TestCase->runTest()|nin RunnerOutput/tcCases/testCase.phptx(34) Tester\TestCase->run()' flowId='%a%RunnerOutput/tcCases/testCase.phptx']

##teamcity[testFinished name='testFail' duration='%f%' flowId='%a%RunnerOutput%ds%tcCases%ds%testCase.phptx']

##teamcity[testStarted name='testSkip' locationHint='tester_method://%a%RunnerOutput%ds%tcCases%ds%testCase.phptx#testSkip' flowId='%a%RunnerOutput%ds%tcCases%ds%testCase.phptx']

##teamcity[testIgnored name='testSkip' message='' flowId='%a%RunnerOutput%ds%tcCases%ds%testCase.phptx']

##teamcity[testFinished name='testSkip' duration='%f%' flowId='%a%RunnerOutput%ds%tcCases%ds%testCase.phptx']

##teamcity[testSuiteFinished name='RunnerOutput%ds%tcCases%ds%testCase.phptx' flowId='%a%RunnerOutput%ds%tcCases%ds%testCase.phptx']
TC;

Assert::match($expected, $output);
