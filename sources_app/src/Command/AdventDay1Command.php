<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

const MAIN_DATA_DIR = '/var/data/';
#[AsCommand(
  name: 'advent:day1',
  description: 'Advent day 1',
)]
class AdventDay1Command extends Command
{
  protected function configure(): void
  {
    $this
      ->addOption('file', '-f', InputOption::VALUE_REQUIRED, 'file value');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);

    $inputFilePath = $input->getOption('file');
    if (!$inputFilePath) {
      $inputFilePath = MAIN_DATA_DIR . 'day1/input';
    }
    $io->note(sprintf('Input file path is : %s', $inputFilePath));

    $contentInput = file($inputFilePath);
    $totalElfCarriedCalories = 0;
    $tabCalories = [];
    foreach ($contentInput as $contentLine) {
      $bagCalories = intval($contentLine);
      if ($bagCalories === 0) {
        $tabCalories[] = $totalElfCarriedCalories;
        $totalElfCarriedCalories = 0;
      } else {
        $totalElfCarriedCalories += $bagCalories;
      }
    }
    rsort($tabCalories);
    $io->info(sprintf('Better elf is carrying %s calories', $tabCalories[0]));
    $io->info(sprintf('3 elves carried max calories for %s', $tabCalories[0] + $tabCalories[1] + $tabCalories[2]));

    return Command::SUCCESS;
  }
}
