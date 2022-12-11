<?php

namespace App\Command;

use Ds\Stack;
use http\Exception\InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Exception\InvalidParameterException;

#[AsCommand(
    name: 'advent:day6',
    description: 'Advent day 6',
)]
class AdventDay6Command extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $inputContent = file('/var/data/day6/input');
        $inputMessage = rtrim($inputContent[0]);

        $StepOneResponse = 0;

        for ($i = 0; $i < strlen($inputMessage); $i++) {
            if ($this->isAllDifferent(substr($inputMessage, $i, 14))) {
                $io->success(sprintf('Response to Step one is %s', $i+14));
                return Command::SUCCESS;

            }
        }

        $io->error('Cannot find how to do it');
        return Command::FAILURE;
    }

    private function isAllDifferent(string $input): bool
    {
        if (strlen($input) !== 14)
            throw new InvalidArgumentException('String must be 14 character long');

        for ($i = 1; $i < 14; $i++) {
            $current = substr($input, $i, 1);
            if (str_contains(substr($input, 0, $i), $current) ||
                str_contains(substr($input, $i + 1), $current)) {
                return false;
            }
        }
        return true;
    }


}
