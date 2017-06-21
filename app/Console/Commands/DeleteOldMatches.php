<?php

namespace App\Console\Commands;

use App\Models\Match;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matches:deleteOld';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old matches (older than 1 week)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo 'hello';
        $matches = Match::all();
        // dump($matches);
        echo "\r\n";

        $date = new Carbon("7 days ago");
        dump($date);
        echo $date->format('d/m/y');

        /*
        foreach ($matches as $match) {
            if ($match->date < '24/06/17') {
                echo $match->name;
                echo " - on ";
                echo $match->date;
                echo "\r\n";
            }
        } */

    }
}
