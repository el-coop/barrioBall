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
	 *
	 * @return mixed
	 */
	public function handle(): void {
		$matches = Match::where('date_time', '<', new Carbon("7 days ago"))->get();

		foreach ($matches as $match) {
			$match->delete();
			event(new DeletedOldMatch($match));
		}
	}
}
