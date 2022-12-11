<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Exception\InvalidParameterException;

#[AsCommand(
    name: 'advent:day4',
    description: 'Advent day 3',
)]
class AdventDay4Command extends Command
{
    const ELF_1= 0;
    const ELF_2 = 1;
    const START = 0;
    const END = 1;
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contentInput = file('/var/data/day4/input');
        $step1Result = 0;
        $step2Result = 0;

        foreach ($contentInput as $numLine => $pair) {
            $sections = array_map(fn($n) => explode('-', $n), array_map('trim', explode(",", $pair)));
            /** Step 1 */
            if (
                (
                    $sections[self::ELF_1][self::START] <= $sections[self::ELF_2][self::START] &&
                    $sections[self::ELF_1][self::END] >= $sections[self::ELF_2][self::END]) ||
                (
                    $sections[self::ELF_2][self::START] <= $sections[self::ELF_1][self::START] &&
                    $sections[self::ELF_2][self::END] >= $sections[self::ELF_1][self::END]
                )
            ) {
                $step1Result++;
            }
            /** step2 */
            if (
                (
                    $sections[self::ELF_1][self::START] <= $sections[self::ELF_2][self::START] &&
                    $sections[self::ELF_1][self::END] >= $sections[self::ELF_2][self::START]
                ) ||
                (
                    $sections[self::ELF_2][self::START] <= $sections[self::ELF_1][self::START] &&
                    $sections[self::ELF_2][self::END] >= $sections[self::ELF_1][self::START]
                ) ||
                (
                    $sections[self::ELF_1][self::START] <= $sections[self::ELF_2][self::END] &&
                    $sections[self::ELF_1][self::END] >= $sections[self::ELF_2][self::END]
                ) ||
                (
                    $sections[self::ELF_2][self::START] <= $sections[self::ELF_1][self::END] &&
                    $sections[self::ELF_2][self::END] >= $sections[self::ELF_1][self::END]
                )
            ) {
                $step2Result++;
            }
        }
        $io->info("Result Step 1 is $step1Result");
        $io->info("Result Step 2 is $step2Result");
        return Command::SUCCESS;
    }


}
