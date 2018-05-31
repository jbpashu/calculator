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
		[['operation' 	=> 'add', 'value1' 	=> '1']],
		[['operation' 	=> 'add', 'value1' 	=> '2,3']],
		[['operation' 	=> 'add', 'value1' 	=> '4,5,6']],
		[['operation' 	=> 'add', 'value1' 	=> '2,3,4,5']],
		[['operation' 	=> 'add', 'value1' 	=> '4,7,3,4,7,5,6,7,4,3,2,5,7,5,3,4,6,7,8,9,5,5,5,4,2,3']]
	];
    }

	
    public function testSumWithoutValues()
    {
        $this->assertContains('0', $this->executeCommand([
			'operation' 	=> 'add',
			'value1' 	=> '',
			'value1' 	=> '',
		], []));

    }

    public function testSumWithOneValue()
    {
	$this->assertContains('1', $this->executeCommand([
			'operation' 	=> 'add',
			'value1' 	=> '1',
		], []));
    }

    public function testSumWithAllValues()
    {
        $this->assertContains('5',$this->executeCommand([
		
			'operation' 	=> 'add',
			'value1' 	=> '2,3',
		], []));
    }
	
    /**
	*@dataProvider provideData
	*/
    public function testAddWithDataProvider($data) {
		$sum = array_sum(explode(',', $data['value1']));
		$this->assertContains((string)$sum, $this->executeCommand($data, []));
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
