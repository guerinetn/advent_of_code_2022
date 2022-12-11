<?php

namespace App\Command;

use App\Model\Directory;
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
    name: 'advent:day7',
    description: 'Advent day 7',
)]
class AdventDay7Command extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $inputContent = file('/var/data/day7/input');
        $currentPosition = new Stack();
        $responseStepOne = 0;
        foreach ($inputContent as $instruction) {
            if (str_starts_with($instruction, '$ cd ..')) {
                $currentDir = $currentPosition->pop();

                if ($currentDir->size <= 100000) {
                    $responseStepOne += $currentDir->size;
                }

                $parentDir = $currentPosition->pop();
                $parentDir->size += $currentDir->size;
                $currentPosition->push($parentDir);

            } elseif (str_starts_with($instruction, '$ cd')) {
                $currentPosition->push(new Directory(rtrim(substr($instruction, 4))));
            } elseif (str_starts_with($instruction, '$ ls')) {
//                $io->comment('osef ' . rtrim($instruction));
                continue;
            } elseif (str_starts_with($instruction, 'dir')) {
//                $io->comment('osef ' . rtrim($instruction));
                continue;
            } else {
                $elements = explode(' ', $instruction);
                $currentDir = $currentPosition->pop();
                $currentDir->size += intval($elements[0]);
                $currentPosition->push($currentDir);
            }
        }
        $currentDir = null;
        while (!$currentPosition->isEmpty()) {
            $currentDir = $currentPosition->pop();

            if ($currentDir->size <= 100000) {
                $responseStepOne += $currentDir->size;
            }
            if (!$currentPosition->isEmpty()) {
                $parentDir = $currentPosition->pop();
                $parentDir->size += $currentDir->size;
                $currentPosition->push($parentDir);
            }
        }

        $io->success('Response step one ' . $responseStepOne);
        echo $currentDir;
        $spaceNeeded = 30000000 - 70000000 + $currentDir->size;
        $io->info('space need is ' . $spaceNeeded);

        $responseStepTwo = new Directory('update', 30000000);


        foreach ($inputContent as $instruction) {
            if (str_starts_with($instruction, '$ cd ..')) {
                $currentDir = $currentPosition->pop();

                if (
                    ($currentDir->size >= $spaceNeeded) &&
                    ($currentDir->size < $responseStepTwo->size)
                ) {
                    $responseStepTwo = $currentDir;
                }

                $parentDir = $currentPosition->pop();
                $parentDir->size += $currentDir->size;
                $currentPosition->push($parentDir);

            } elseif (str_starts_with($instruction, '$ cd')) {
                $currentPosition->push(new Directory(rtrim(substr($instruction, 4))));
            } elseif (str_starts_with($instruction, '$ ls')) {
                continue;
            } elseif (str_starts_with($instruction, 'dir')) {
                continue;
            } else {
                $elements = explode(' ', $instruction);
                $currentDir = $currentPosition->pop();
                $currentDir->size += intval($elements[0]);
                $currentPosition->push($currentDir);
            }
        }
        $io->success("Response step two is : " . $responseStepTwo);
        return Command::SUCCESS;
    }
}
