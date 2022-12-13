<?php

namespace App\Command;

use App\Model\Directory;
use App\Model\Tree;
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

/**
 * @TODO with recursive mode
 */
#[AsCommand(
    name: 'advent:day8',
    description: 'Advent day 7',
)]
class AdventDay8Command extends Command
{
    private array $treeMatrix;

    private bool $debug = false;

    private SymfonyStyle $io;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->treeMatrix = [];
        $this->io = new SymfonyStyle($input, $output);

        foreach (file('/var/data/day8/input') as $line) {
            /** Could be used or not for recursiveMode */
            $this->treeMatrix[] = array_map(fn($param) => new Tree($param), str_split(rtrim($line), 1));
            $this->treeMatrix[] = array_map(fn($param) => $param, str_split(rtrim($line), 1));
        }
        $this->io->info("Number of tree : " . sizeof($this->treeMatrix) * sizeof($this->treeMatrix[0]));

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $responseStepOne = sizeof($this->treeMatrix) * 4 - 4;
        $responseStepTwo = 0;
        for ($y = 1; $y < sizeof($this->treeMatrix) - 1; $y++) {
            for ($x = 1; $x < sizeof($this->treeMatrix[0]) - 1; $x++) {
                if (
                    $this->isVisibleOnTop($y, $x) ||
                    $this->isVisibleOnRight($y, $x) ||
                    $this->isVisibleOnBottom($y, $x) ||
                    $this->isVisibleOnLeft($y, $x)
                ) {
                    $responseStepOne++;
                }
                $scoreCurrentTree = $this->getLeftScore($y, $x) * $this->getBottomScore($y, $x) *
                    $this->getTopScore($y, $x) * $this->getRightScore($y, $x);
                if ($responseStepTwo <= $scoreCurrentTree) {
                    $responseStepTwo = $scoreCurrentTree;
                }
            }
        }

        $this->io->success("Response step One is : " . $responseStepOne);
        $this->io->success("Response step Two is : " . $responseStepTwo);

        return Command::SUCCESS;
    }

    private function isVisibleOnLeft(int $y, int $x): bool
    {
        for ($left = 0; $left < $x; $left++) {
            if ($this->treeMatrix[$y][$left] >= $this->treeMatrix[$y][$x]) {
                return false;
            }
        }
        return true;
    }

    private function isVisibleOnRight(int $y, int $x): bool
    {
        for ($right = sizeof($this->treeMatrix) - 1; $right > $x; $right--) {
            if ($this->treeMatrix[$y][$right] >= $this->treeMatrix[$y][$x]) {
                return false;
            }
        }
        return true;
    }

    private function isVisibleOnTop(int $y, int $x): bool
    {
        for ($top = 0; $top < $y; $top++) {
            if ($this->treeMatrix[$top][$x] >= $this->treeMatrix[$y][$x]) {
                return false;
            }
        }
        return true;
    }

    private function isVisibleOnBottom(int $y, int $x): bool
    {
        for ($bottom = sizeof($this->treeMatrix) - 1; $bottom > $y; $bottom--) {
            if ($this->treeMatrix[$bottom][$x] >= $this->treeMatrix[$y][$x]) {
                return false;
            }
        }
        return true;
    }

    private function getLeftScore(int $y, int $x): int
    {
        for ($left = $x - 1; $left >= 0; $left--) {
            if ($this->treeMatrix[$y][$left] >= $this->treeMatrix[$y][$x]) {
                if ($this->debug) {
                    $this->io->comment("DEBUG x = $x, left = $left");
                }
                return $x - $left;
            }
        }
        return $x;
    }

    private function getRightScore(int $y, int $x): int
    {
        for ($right = $x + 1; $right < sizeof($this->treeMatrix); $right++) {
            if ($this->treeMatrix[$y][$right] >= $this->treeMatrix[$y][$x]) {
                if ($this->debug) {
                    $this->io->comment("DEBUG x = $x, right = $right");
                }
                return $right - $x;
            }
        }
        return sizeof($this->treeMatrix) - 1 - $x;
    }

    private function getTopScore(int $y, int $x): int
    {
        for ($top = $y - 1; $top >= 0; $top--) {
            if ($this->treeMatrix[$top][$x] >= $this->treeMatrix[$y][$x]) {
                if ($this->debug) {
                    $this->io->comment("DEBUG x = $x, top = $top");
                }
                return $y - $top;
            }
        }
        return $y;
    }

    private function getBottomScore(int $y, int $x): int
    {
        for ($bottom = $y + 1; $bottom < sizeof($this->treeMatrix); $bottom++) {
            if ($this->treeMatrix[$bottom][$x] >= $this->treeMatrix[$y][$x]) {
                if ($this->debug) {
                    $this->io->comment("DEBUG x = $x, bottom = $bottom");
                }
                return $bottom - $y;
            }
        }
        return sizeof($this->treeMatrix) - 1 - $y;
    }

    private function isOnEdge(int $y, int $x): bool
    {
        return (
            ($x === 0) ||
            $x === (sizeof($this->treeMatrix[0]) - 1) ||
            $y === 0 ||
            $y === (sizeof($this->treeMatrix) - 1)
        );
    }

}
