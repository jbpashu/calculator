<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Utils\Calculator;

class AppCalculatorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:calculator')
            ->setDescription('an easy and simple calculator')
            ->addArgument('delimeter', InputArgument::REQUIRED, 'Delimeter String')
            ->addArgument('operation', InputArgument::REQUIRED, 'Sum operation')
            ->addArgument('value1', InputArgument::OPTIONAL, 'First number')
            ->addArgument('value2', InputArgument::OPTIONAL, 'Second number')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $delimeter = $input->getArgument('delimeter');
        $operation = $input->getArgument('operation');
        $argument1 = $input->getArgument('value1');
        $argument2 = $input->getArgument('value2');

	$objCalculator = new Calculator($delimeter, $argument1);

	if ($objCalculator->checkNegetaiveNumbers()) {
	    $output->writeln(sprintf('Error: Negative numbers (%s) not allowed.', implode(',', $objCalculator->getNegativeNumbers())));
	    //exit;
	} else {
	
		switch(strtolower($operation)) {
			case 'add': $output->writeln($objCalculator->sum()); break;
			case 'multiply': $output->writeln($objCalculator->multiply()); break;
			default	  : break;
		}
	}
    }

}
