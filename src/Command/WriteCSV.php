<?php

declare(strict_types=1);

namespace App\Command;

use Exception;
use App\Core\Flusher;
use App\Entity\Scope;
use App\Repository\ScopeRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(name: 'writeCsv')]
class WriteCSV extends Command
{

    public function __construct(
        private readonly Flusher $flusher,
        private readonly ScopeRepository $scopeRepository,
        private readonly ParameterBagInterface $parameterBag
    ) {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $baseDir = $this->parameterBag->get('kernel.project_dir') . '/test_data.csv';
        $fileContent = file_get_contents($baseDir);

        if (false === $fileContent) {
            throw new Exception('csv file not found');
        }

        $rows = $this->csvToArray($fileContent);
        /** @var array<int, array{min: string, max: string}> $row */
        foreach ($rows as $row) {
            if (!$row['min'] || !$row['max']) {
                continue;
            }

            $scope = new Scope(
                minValue: (int) $row['min'],
                maxValue: (int) $row['max']
            );
            $this->scopeRepository->save($scope);
        }

        $this->flusher->flush();

        return 0;
    }

    /**
     * @throws Exception
     */
    private function csvToArray(string $fileContent): array
    {
        $rows = explode(PHP_EOL, $fileContent);
        if (!is_array($rows)) {
            throw new Exception('file content is not array');
        }

        /** service column deleted */
        unset($rows[0]);

        return array_reduce($rows, function($acc, $row){
            $array = explode(',', $row);
            if (is_array($array) && count($array) >= 3) {
                $acc[] = [
                    'min' => $array[1],
                    'max' => $array[2]
                ];
            }

            return $acc;
        }, []);
    }
}
