<?php

namespace App\Console\Commands;

use App\Models\Match;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Notifications\Match\NotifyBeforeMatch as Notification;

class NotifyBeforeMatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'match:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notification 1 hour before match';

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
        $matches = Match::whereBetween('date_time',[Carbon::now()->addMinute(55),Carbon::now()->addMinute(65)])->get();
        foreach ($matches as $match){
            $users = $match->registeredPlayers;
            foreach ($users as $user){
                $user->notify(new Notification($match,$user));
            }
        }
    }
}
