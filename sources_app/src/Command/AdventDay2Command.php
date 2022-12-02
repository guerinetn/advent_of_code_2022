<?php

namespace App\Command;


const ROCK_X = 'A';
const ROCK_Y = 'X';
const PAPER_X = 'B';
const PAPER_Y = 'Y';
const SCISSOR_X = 'C';
const SCISSOR_Y = 'Z';

const ROCK_SCORE = 1;
const PAPER_SCORE = 2;
const SCISSOR_SCORE = 3;

const WIN_SCORE = 6;
const LOSE_SCORE = 0;
const DRAW_SCORE = 3;

const LOSE = 'X';
const DRAW = 'Y';
const WIN = 'Z';
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Exception\InvalidParameterException;


#[AsCommand(
  name: 'advent:day2',
  description: 'Advent day 2',
)]
class AdventDay2Command extends Command
{
  protected function configure(): void
  {
    $this
      ->addOption('file', '-f', InputOption::VALUE_REQUIRED, 'file value');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);

    $contentInput = file('/var/data/day2/input');
    $part1Score = 0;
    $part2Score = 0;
    foreach ($contentInput as $contentLine) {
      $game = explode(" ", trim($contentLine));

      $part1Score += $this->calculateStrategy1($game[0], $game[1]) + $this->getScorePlayed($game[1]);
      $part2Score += $this->calculateStrategy2($game[0], $game[1]);
    }
    $io->info(sprintf("Your score %s with strategy1", $part1Score));
    $io->info(sprintf("Your score %s with strategy2", $part2Score));

    return Command::SUCCESS;
  }

  private function getScorePlayed(string $played): int
  {
    return match ($played) {
      ROCK_Y => ROCK_SCORE,
      PAPER_Y => PAPER_SCORE,
      SCISSOR_Y => SCISSOR_SCORE,
      default => throw new InvalidParameterException('invalid game chose PAPER, ROCK or SCISSOR')
    };
  }

  private function calculateStrategy1(string $player, string $me): int
  {
    return match ($player) {
      ROCK_X => match ($me) {
        ROCK_Y => DRAW_SCORE,
        PAPER_Y => WIN_SCORE,
        SCISSOR_Y => LOSE_SCORE,
        default => throw new InvalidParameterException('invalid game chose PAPER, ROCK or SCISSOR for game played')
      },
      PAPER_X => match ($me) {
        ROCK_Y => LOSE_SCORE,
        PAPER_Y => DRAW_SCORE,
        SCISSOR_Y => WIN_SCORE,
        default => throw new InvalidParameterException('invalid game chose PAPER, ROCK or SCISSOR  for game played')
      },
      SCISSOR_X => match ($me) {
        ROCK_Y => WIN_SCORE,
        PAPER_Y => LOSE_SCORE,
        SCISSOR_Y => DRAW_SCORE,
        default => throw new InvalidParameterException('invalid game chose PAPER, ROCK or SCISSOR  for game played')
      }
    };
  }

  private function calculateStrategy2(string $player, string $gameResult): int
  {
    return match ($gameResult) {
      WIN => WIN_SCORE + match ($player) {
          SCISSOR_X => ROCK_SCORE,
          ROCK_X => PAPER_SCORE,
          PAPER_X => SCISSOR_SCORE,
        },
      DRAW => DRAW_SCORE + match ($player) {
          SCISSOR_X => SCISSOR_SCORE,
          ROCK_X => ROCK_SCORE,
          PAPER_X => PAPER_SCORE,
        },
      LOSE => LOSE_SCORE + match ($player) {
          SCISSOR_X => PAPER_SCORE,
          ROCK_X => SCISSOR_SCORE,
          PAPER_X => ROCK_SCORE,
        }
    };
  }
}
