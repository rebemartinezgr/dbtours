<?php
/**
 * @author Rebeca Martínez García
 * @copyright  Copyright © 2018  Discover Barcelona
 */

declare(strict_types=1);

namespace Dbtours\Calendar\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use GrinsteinDavid\GoogleCalendar\CalendarFactory;
use GrinsteinDavid\GoogleCalendar\EventFactory;

/**
 * Class class SyncCalendar extends Command
 */
class SyncCalendar extends Command
{

    private $calendarFactory;

    private $eventFactory;


    public function __construct(
        CalendarFactory $calendar,
        EventFactory $eventFactory
    ) {
        $this->calendarFactory = $calendar;
        $this->eventFactory = $eventFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('db:calendar:sync')
            ->setDescription('Sync Google Calendar')
            ->setAliases(['c:s']);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Running Dbtours\Calendar\Console\SyncCalendar::execute');
        try {
            $this->prueba();
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
        $output->writeln('Dbtours\Calendar\Console\SyncCalendar::execute finish without Exceptions');
    }

    private function prueba()
    {
        $calendar = $this->calendarFactory->create();

        $calendar->summary = 'Summer';
        $calendar->save();

        $event = $this->eventFactory->create(['calendarId' => $calendar->id]);
//        $event = new Event($calendar->id);
        $event->timeZone = "America/New_York";
        $event->summary = 'First Event';
        $event->startDateTime = date("Y-m-d H:i:s", strtotime('+1 hours'));
        $event->endDateTime = date("Y-m-d H:i:s", strtotime('+4 hours'));
        $event->save();

        foreach ($calendar->events() as $event) {
            $event->description = 'Hottest summer!';
            array_push($event->attendees, [
                'email' => 'r.martinezgr@gmail.com',
                'displayName' => 'example 3'
            ]);

            $event->save(); // UPDATED BY ATTRS
        }

//        $calendar2 = new Calendar($calendarId);
//
//        $events = $calendar2->events();
//
//        $event = new Event($calendar2->id, $calendar2->events[0]->id);
//        $event->organizerEmail = 'example1@email.com';
//        $event->organizerName = "David Miranda Grinstein";
//        $event->guestsCanInviteOthers = true;
//        $event->guestsCanModify = true;
//        $event->guestsCanSeeOtherGuests = true;
//        $event->anyoneCanAddSelf = true;
//        $event->attendees = [
//            [
//                'email' => 'example1@email.com',
//                'displayName' => 'example 1'
//            ],
//            [
//                'email' => 'example2@email.com',
//                'displayName' => 'example 2'
//            ]
//        ];
//        $event->save();
    }
}

