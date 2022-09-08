<?php

namespace App\Command;

use App\Service\IndexingService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;

#[AsCommand(
    name: 'app:indexing',
    description: 'Add a short description for your command',
)]
class IndexingCommand extends Command
{
    public function __construct(
        private readonly IndexingService $indexingService,
        string $name = null
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $finder = new Finder();
//        $finder->files()->in('/var/www/sortiment');

$finder =
    [
        new File('/var/www/sortiment/params_235000.txt')
    ];
        $indexingResult = $this->indexingService->indexingFiles($finder);

        dump($indexingResult);

        return Command::SUCCESS;
    }
}
