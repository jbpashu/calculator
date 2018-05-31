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

    public function provideData()
    {
	return [
[['delimeter' 	=> '\\,\\','operation' 	=> 'add', 'value1' 	=> '1']],
[['delimeter' 	=> '\\,\\','operation' 	=> 'add', 'value1' 	=> '2,3']],
[['delimeter' 	=> '\\,\\','operation' 	=> 'add', 'value1' 	=> '4,5,6']],
[['delimeter' 	=> '\\,\\','operation' 	=> 'add', 'value1' 	=> '2,3,4,5']],
[['delimeter' 	=> '\\,\\','operation' 	=> 'add', 'value1' 	=> '4,7,3,4,7,5,6,7,4,3,2,5,7,5,3,4,6,7,8,9,5,5,5,4,2,3']],
[['delimeter' 	=> '\\,\\','operation' 	=> 'add', 'value1' 	=> '2\n,3,4']],
[['delimeter' 	=> '\\;\\','operation' 	=> 'add', 'value1' 	=> '3;4;5']],
	];
    }    
	
    /**
     *@dataProvider provideData
     */
    public function testAddWithDataProvider($data) {
	$seperator = ',';
	if(!empty($data['delimeter'])) {
		$seperator = str_replace('\\', '', $data['delimeter']);
	}
	$sum = array_sum(explode($seperator, $data['value1']));
	$this->assertContains((string)$sum, $this->executeCommand($data, []));
    }

    public function testAddWithNegetaiveNumbers() {
	$data = [
		'delimeter' 	=> '\\,\\',
		'operation' 	=> 'add', 
		'value1' 	=> '2,7,-3,5,-2'
	];
	$seperator = ',';
	if(!empty($data['delimeter'])) {
		$seperator = str_replace('\\', '', $data['delimeter']);
	}
	$sum = array_sum(explode($seperator, $data['value1']));
	$this->assertContains('Error: Negative numbers not allowed.', $this->executeCommand($data, []));
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
	
	return $commandTester->getDisplay(); 
    }
}
