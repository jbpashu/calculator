<?php

namespace Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use AppBundle\Command\AppCalculatorCommand;

class AppCalculatorCommandTest extends KernelTestCase
{

    protected function setUp()
    {
        exec('stty 2>&1', $output, $exitcode);
        $isSttySupported = 0 === $exitcode;

        $isWindows = '\\' === DIRECTORY_SEPARATOR;

        if ($isWindows || !$isSttySupported) {
            $this->markTestSkipped('`stty` is required to test this command.');
        }
    }

    public function provideOnlyOperationData()
    {
	return 
		[
			'operation' 	=> 'sum',
			'value1' 	=> '',
			'value1' 	=> '',
		];
    }

    public function provideOnlyOperationAndFirstValueData()
    {
	return 
		[
			'operation' 	=> 'sum',
			'value1' 	=> '1',
			'value1' 	=> '',
		];
    }

    public function provideAllData()
    {
	return 
		[
		
			'operation' 	=> 'sum',
			'value1' 	=> '2',
			'value1' 	=> '3',
		];
    }

	
    public function testSumWithoutValues()
    {
        $this->executeCommand([
			'operation' 	=> 'sum',
			'value1' 	=> '',
			'value1' 	=> '',
		], []);

    }

    public function testSumWithOneValue()
    {
	$this->executeCommand([
			'operation' 	=> 'sum',
			'value1' 	=> '1',
			'value1' 	=> '',
		], []);
    }

    public function testSumWithAllValues()
    {
        $this->executeCommand([
		
			'operation' 	=> 'sum',
			'value1' 	=> '2',
			'value1' 	=> '3',
		], []);
    }



    /**
     * This helper method abstracts the boilerplate code needed to test the
     * execution of a command.
     *
     * @param array $arguments All the arguments passed when executing the command
     * @param array $inputs    The (optional) answers given to the command when it asks for the value of the missing arguments
     */
    private function executeCommand(array $arguments, array $inputs = [])
    {
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $command = new AppCalculatorCommand();
        $command->setApplication(new Application(self::$kernel));

        $commandTester = new CommandTester($command);
        $commandTester->setInputs($inputs);
        $commandTester->execute($arguments);
    }
}
