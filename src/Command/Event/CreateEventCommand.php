<?php

namespace App\Command\Event;

use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\DBAL\Connection;

#[AsCommand(
    name: 'app:CreateEventCommand',
    description: 'Command that we will run as a cronjob to create MySQL events to archive sorties that are older than 30 days.',
)]
class CreateEventCommand extends Command
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // get current time
        $io->writeln("-------- now = " . date("Y-m-d H:i:s"));

        // get all sorties that doesn't have value in the column "eventIsCreated" 
        $sortiesQuery = "Select s.id, s.event_is_created, s.date
                        FROM sortie as s
                        WHERE s.event_is_created IS NULL";
        
        $stmt = $this->connection->prepare($sortiesQuery);
        $sorties = $stmt->executeQuery()->fetchAllAssociative();

        // dump résultat de la première query
        if($nbSorties = count($sorties) > 0) {
            $headers = ['id', 'event_is_created', 'creation_date'];
            
            $io->writeln("-------- " . ($nbSorties + 1) . " sorties results");
            $io->table($headers, $sorties);
        } else {
            $io->warning('No sorties found');
        }

        $sortieIds = '';
        
        // create event foreach sortie to archive sortie
        foreach($sorties as $id => $sortie) {
            $id++;

            if($id == count($sorties)){
                $sortieIds .= $sortie['id'];
            } else {
                $sortieIds .= $sortie['id'] . ", ";
            }

            $sortieId = $sortie['id'];
            $eventName = "archive_sortie_".$sortieId;
            $scheduleDateTime = new DateTime(date('Y-m-d H:i:s', strtotime($sortie['date'] . " +30 days")));
            $schedule = $scheduleDateTime->format('Y-m-d H:i:s');
            
            $sql = "CREATE EVENT IF NOT EXISTS ".$eventName."
                    ON SCHEDULE AT '$schedule'
                    DO
                    BEGIN
                        UPDATE sortie
                        SET etat_id = '7'
                        WHERE id = $sortieId;
                    END";
            $stmt = $this->connection->prepare($sql);
            $stmt->executeQuery();
            
            $io->writeln($eventName . " event created to run at " . $schedule);
        }

        // set event_is_created to 1
        if($sortieIds) {
            $updateSortieQuery = "UPDATE sortie
                SET event_is_created = 1
                WHERE id IN (" . $sortieIds . ")";
            $stmt = $this->connection->prepare($updateSortieQuery);
            $stmt->executeQuery();

            $io->writeln("updated sorties with ids IN (".$sortieIds.") to set event_is_created attribute to 1");
        }

        $io->success('Command executed. Pass --help to see your options.');
        return Command::SUCCESS;
    }
}
