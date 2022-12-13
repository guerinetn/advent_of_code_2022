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

#[AsCommand(
    name: 'advent:day8',
    description: 'Advent day 7',
)]
class AdventDay8Command extends Command
{
    private array $treeMatrix;


    private int $responseStepOne = 0;
    private int $responseStepTwo = 0;

    private SymfonyStyle $io;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->treeMatrix = [];
        $this->io = new SymfonyStyle($input, $output);

        foreach (file('/var/data/day8/input') as $line) {

            $this->treeMatrix[] = array_map(fn($param) => ["height" => $param, 'topHeight' => 0, 'visible'=> true], str_split(rtrim($line), 1));
        }
        $this->io->info('file loaded');
        $this->responseStepOne = sizeof($this->treeMatrix) * sizeof($this->treeMatrix[0]);
        $this->io->info("Number of tree : " . $this->responseStepOne);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->treeMatrix as $y => $lines) {
            $this->io->comment("line $y");
            foreach ($lines as $x => $tree) {
                if (!$this->isVisibleTopLeft($y, $x)) {
                    $this->responseStepOne--;
                }
            }
        }

        for ($y = sizeof($this->treeMatrix) - 1; $y >= 0; $y--) {
            for ($x = sizeof($this->treeMatrix[$y]) - 1; $x >= 0; $x--) {
                if (!$this->isVisibleBottomRight($y, $x)) {
                    $this->responseStepOne--;
                }
            }
        }
        $this->io->success("Response step One is : " . $this->responseStepOne);
        $this->io->success("Response step two is : " . $this->responseStepTwo);
        return Command::SUCCESS;
    }


    private function isVisibleTopLeft(int $y, int $x): bool
    {
        if ($this->isOnEdge($y, $x)) {
            $this->treeMatrix[$y][$x]['topHeight'] = $this->treeMatrix[$y][$x];
            return true;
        }
        /* Top Left is Higher or hide by a bigger one*/
        if (
            $this->treeMatrix[$y][$x]['height'] <= $this->treeMatrix[$y - 1][$x]['height'] ||
            $this->treeMatrix[$y][$x]['height'] <= $this->treeMatrix[$y - 1][$x]['topHeight'] ||
            $this->treeMatrix[$y][$x]['height'] <= $this->treeMatrix[$y][$x - 1]['height'] ||
            $this->treeMatrix[$y][$x]['height'] <= $this->treeMatrix[$y][$x - 1]['topHeight']
        ) {
            return false;
        }
        return true;
    }

    private function isVisibleBottomRight(int $y, int $x): bool
    {
        if ($this->isOnEdge($y, $x)) {
            $this->treeMatrix[$y][$x]['topHeight'] = $this->treeMatrix[$y][$x]['height'];
            return true;
        }
        /* Top Left is Higher or hide by a bigger one*/
        if (
            $this->treeMatrix[$y][$x]['height'] <= $this->treeMatrix[$y + 1][$x]['height'] ||
            $this->treeMatrix[$y][$x]['height'] <= $this->treeMatrix[$y + 1][$x]['topHeight'] ||
            $this->treeMatrix[$y][$x]['height'] <= $this->treeMatrix[$y][$x + 1]['height'] ||
            $this->treeMatrix[$y][$x]['height'] <= $this->treeMatrix[$y][$x + 1]['topHeight']
        ) {
            return false;
        }
        return true;
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
