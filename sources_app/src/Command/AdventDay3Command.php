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
    name: 'advent:day3',
    description: 'Advent day 3',
)]
class AdventDay3Command extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contentInput = file('/var/data/day3/input');
        $step1Result = 0;
        $step2Result = 0;
        $elfGroup = [];
        foreach ($contentInput as $numLine => $backpack) {
            /** Step 1 */
            $backpack = trim($backpack);
            $compartimentSize = strlen($backpack) / 2;
            $compartiment1 = substr($backpack, 0, $compartimentSize);
            $compartiment2 = substr($backpack, $compartimentSize, $compartimentSize);
            $step1Result += $this->getCommonItems($compartiment1, $compartiment2);

            /* step2 */
            $elfGroup[$numLine % 3] = $backpack;
            if (2 == $numLine % 3) {
                $step2Result += $this->getCommonItems(
                    $this->getCommonItems($elfGroup[0], $elfGroup[1], false),
                    $elfGroup[2]
                );
                foreach ($elfGroup as $elf) {
                    $io->comment(sprintf('elf carry %s in his backpack', $elf));
                }
                $io->info(
                    sprintf(
                        'Elf 1 and 2 have %s items in common and have %s in common with 3',
                        $this->getCommonItems($elfGroup[0], $elfGroup[1], false),
                        $this->getCommonItems(
                            $this->getCommonItems($elfGroup[0], $elfGroup[1], false),
                            $elfGroup[2],
                            false
                        )
                    )
                );
            }
        }
        $io->info("Result Step 1 is $step1Result");
        $io->info("Result Step 2 is $step2Result");
        return Command::SUCCESS;
    }

    private function getPriority(string $char): int
    {
        if (1 !== strlen($char)) {
            throw new InvalidParameterException("Char $char is invalid null or too long");
        }
        if (ord($char) > 96) {
            return ord($char) - 96;
        } else {
            return ord($char) - 38;
        }
    }

    private function getCommonItems(string $item1, string $item2, bool $resultOrCommon = true): int|string
    {
        $item1 = str_split($item1);
        sort($item1);
        $previousChar = '';
        $commonItems = '';
        $prioritySum = 0;
        foreach ($item1 as $chr) {
            if ($chr !== $previousChar && str_contains($item2, $chr)) {
                $commonItems .= $chr ;
                $prioritySum += $this->getPriority($chr);
            }
            $previousChar = $chr;
        }
        return $resultOrCommon ? $prioritySum : $commonItems;
    }
}
