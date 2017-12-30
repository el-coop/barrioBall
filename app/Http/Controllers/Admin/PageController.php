<?php

namespace App\Http\Controllers\Admin;

use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use App\Models\Match;
use App\Models\User;
use Cache;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller {

	/**
	 * @return View
	 */
	public function index(): View {
		$errorsCount = Cache::tags('admin_errors', 'admin_overview')->rememberForever('admin_error_count', function () {
			return Error::count();
		});
		$newErrors = Cache::tags('admin_errors', 'admin_overview')->remember('admin_new_error_count',5, function () {
			return Error::where('created_at', '>', Carbon::now()->subDay())->count();
		});
		$jsErrors = Cache::tags('admin_errors', 'admin_overview')->rememberForever('admin_js_error_count', function () {
			return JsError::count();
		});
		$phpErrors = Cache::tags('admin_errors', 'admin_overview')->rememberForever('admin_php_error_count', function () {
			return PhpError::count();
		});
		$users = Cache::tags('admin_users', 'admin_overview')->rememberForever('admin_user_count', function () {
			return User::count();
		});
		$newUsers = Cache::tags('admin_users', 'admin_overview')->remember('admin_new_user_count',5, function () {
			return User::where('created_at', '>', Carbon::now()->subDay())->count();
		});
		$matches = Cache::tags('admin_matches', 'admin_overview')->rememberForever('admin_match_count', function () {
			return Match::count();
		});
		$newMatches = Cache::tags('admin_matches', 'admin_overview')->remember('admin_new_match_count', 5, function () {
			return Match::where('created_at', '>', Carbon::now()->subDay())->count();
		});

		return view('admin.overview', compact('users', 'newUsers', 'matches', 'newMatches', 'errorsCount', 'newErrors', 'jsErrors', 'phpErrors'));
	}

	public function getUsers(Request $request): LengthAwarePaginator {
		return Cache::tags('admin_users', 'admin_overview')->rememberForever(sha1($request->fullUrl()), function () use ($request) {
			$users = User::select('id','username', 'email','user_type');

			if ($request->filled('sort')) {
				$sort = explode('|', $request->input('sort'));
				$users = $users->orderBy($sort[0], $sort[1]);

			} else {
				$users = $users->latest();
			}

			if ($request->filled('filter')) {
				$filterVal = "%{$request->input('filter')}%";
				$users->where('username', 'like', $filterVal)
					->orWhere('email', 'like', $filterVal);
			}

			return $users->paginate($request->input('per_page'));
		});
	}

	/**
	 * @param Request $request
	 *
	 * @return LengthAwarePaginator
	 */
	public function getMatches(Request $request): LengthAwarePaginator {
		return Cache::tags('admin_matches', 'admin_overview')->rememberForever(sha1($request->fullUrl()), function () use ($request) {
			$matches = Match::query();

			if ($request->filled('sort')) {
				$sort = explode('|', $request->input('sort'));
				$matches = $matches->orderBy($sort[0], $sort[1]);

			} else {
				$matches = $matches->latest();
			}

			if ($request->filled('filter')) {
				$filterVal = "%{$request->input('filter')}%";
				$matches->where('name', 'like', $filterVal);
			}

			return $matches->paginate($request->input('per_page'));
		});
	}
}
