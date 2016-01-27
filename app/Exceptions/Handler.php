<?php namespace App\Exceptions;


use App\Http\Traits\ApiHelpers;
use Dingo\Api\Exception\StoreResourceFailedException;
use Exception;
use HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Bugsnag\BugsnagLaravel\BugsnagExceptionHandler as ExceptionHandler;


class Handler extends ExceptionHandler {

    use ApiHelpers;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
        StoreResourceFailedException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            if ($request->ajax()) {
                return $this->respondNotFound();
            }
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        //As to preserve the catch all
        if ($e instanceof GeneralException) {
            return redirect()->back()->withInput()->withFlashDanger($e->getMessage());
        }

        if ($e instanceof Backend\Access\User\UserNeedsRolesException) {
            return redirect()->route('admin.access.users.edit', $e->userID())->withInput()->withFlashDanger($e->validationErrors());
        }

        //Catch all
        return parent::render($request, $e);
    }
}
