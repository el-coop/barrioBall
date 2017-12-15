<?php

namespace App\Console\Commands;

use App\Events\Match\DeletedOldMatch;
use App\Models\Match;
use App\Notifications\Match\OldMatchDeleted;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldMatches extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'match:deleteOld';

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
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * @return void
	 */
	public function handle(): void {
		$matches = Match::with('managers', 'registeredPlayers')->where('date_time', '<', new Carbon("7 days ago"))->get();

		foreach ($matches as $match) {
			$players = $match->registeredPlayers;
			$managers = $match->managers;
			$id = $match->id;
			$name = $match->name;
			$match->delete();
			event(new DeletedOldMatch($managers,$players,$name,$id));
		}
	}
}
