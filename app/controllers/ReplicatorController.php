<?php


use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;

class ReplicatorController extends ScheduledCommand {

    //your command name, description etc.

    public function schedule(Schedulable $scheduler)
    {
        //every day at 4:17am
        return $scheduler
            ->daily()
            ->hours(4)
            ->minutes(17);
    }

}