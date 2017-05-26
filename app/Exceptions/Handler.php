<?php

namespace App\Exceptions;

use App\Models\Errors\Error;
use App\Models\Errors\PhpError;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use function PHPSTORM_META\type;

class Handler extends ExceptionHandler {
	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		\Illuminate\Auth\AuthenticationException::class,
		\Illuminate\Auth\Access\AuthorizationException::class,
		\Symfony\Component\HttpKernel\Exception\HttpException::class,
		\Illuminate\Database\Eloquent\ModelNotFoundException::class,
		\Illuminate\Session\TokenMismatchException::class,
		\Illuminate\Validation\ValidationException::class,
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception $exception
	 *
	 * @return void
	 */
	public function report(Exception $exception) {

		if ($this->shouldReport($exception) && ! $exception instanceof QueryException) {
			$this->LogException($exception);
		}


		parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Exception $exception
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $exception) {
		return parent::render($request, $exception);
	}

	/**
	 * Convert an authentication exception into an unauthenticated response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Illuminate\Auth\AuthenticationException $exception
	 *
	 * @return \Illuminate\Http\Response
	 */
	protected function unauthenticated($request, AuthenticationException $exception) {
		if ($request->expectsJson()) {
			return response()->json(['error' => 'Unauthenticated.'], 401);
		}

		return redirect()->guest('login');
	}

	/**
	 * @param Exception $exception
	 */
	protected function LogException(Exception $exception) {
		$request = request();
		$error = new Error;
		$phpError = new PhpError;
		if ($request->user()) {
			$error->user_id = $request->user->id;
		}
		$error->page = $request->fullUrl();
		$phpError->message = $exception->getMessage();
		$phpError->exception = json_encode([
			'class' => get_class($exception),
			'message' => $exception->getMessage(),
			'code' => $exception->getCode(),
			'file' => $exception->getFile(),
			'line' => $exception->getLine(),
			'trace' => $exception->getTrace()
		]);
		$phpError->request = json_encode([
			'method' => $request->method(),
			'input' => $request->all(),
			'server' => $request->server(),
			'headers' => $request->header(),
			'cookies' => $request->cookie(),
			'session' => $request->hasSession() ? $request->session()->all() : '',
			'locale' => $request->getLocale()

		]);
		$phpError->save();
		$phpError->error()->save($error);
	}
}
