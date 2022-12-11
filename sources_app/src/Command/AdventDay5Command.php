<?php

namespace App\Command;

use Ds\Stack;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Exception\InvalidParameterException;

#[AsCommand(
    name: 'advent:day5',
    description: 'Advent day 5',
)]
class AdventDay5Command extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $stackDefinition = file('/var/data/day5/stack_def');

        $stacks = [];
        for ($i = 1; $i <= 9; $i++) {

            $stacks[$i] = new Stack();
            $stacks[$i]->allocate(512);
        }

        for ($numLines = sizeof($stackDefinition) - 2; $numLines >= 0; $numLines--) {
            for ($stackNum = 1; $stackNum <= 9; $stackNum++) {
                $currentCrate = substr(substr($stackDefinition[$numLines], ($stackNum - 1) * 4, 4), 1, 1);
                if ('' !== trim($currentCrate)) {
                    $stacks[$stackNum]->push($currentCrate);
                }
            }
        }
        /** @var Stack $stack */
        $moves = file('/var/data/day5/crates_moves');
        foreach ($moves as $move){
            $rawMove = explode(' ',$move);
            $nbCrate = $rawMove[1];
            $stackOrigin = intval($rawMove[3]);
            $stackDest  = intval($rawMove[5]);
            $rawOriginStack = $stacks[$stackOrigin]->toArray();
            for ($i = 0;$i<$nbCrate;$i++){
                $stacks[$stackOrigin]->pop();
                $stacks[$stackDest]->push($rawOriginStack[$nbCrate-$i-1]);
            }
        }
        $StepOneResponse = '';
        foreach ($stacks as $stackNum => $stack){
            $StepOneResponse .= $stack->peek();
        }

        $io->success(sprintf('Response to Step one is %s',$StepOneResponse));

        return Command::SUCCESS;
    }


}
