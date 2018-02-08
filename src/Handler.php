<?php

namespace Rap2hpoutre\Indice;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Exception;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UnexpectedValueException;

/**
 * Class Handler
 * @package Rap2hpoutre\Indice
 */
class Handler extends ExceptionHandler
{

    /**
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];
    /**
     * @var
     */
    private $exception;
    /**
     * @var
     */
    private $errors;

    /**
     * Report or log an exception.
     *
     * @param  \Exception $e
     * @return void
     * @throws Exception
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * HERE BE DRAGONS.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, Exception $e)
    {
        if ($request->expectsJson()) {
            return parent::render($request, $e);
        }
        $this->exception = $e;
        $this->errors = preg_replace(
            '/#([0-9]+)\s+([^(]+)\(([0-9]+)\):\s+(.*)/',
            '<li><span class="text-muted">\\2</span> line&nbsp;\\3<br>\\4<br><br></li>',
            $e->getTraceAsString()
        );

        if ($e instanceof NotFoundHttpException) {
            return $this->view('not-found-http-exception');
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return $this->view('method-not-allowed-http-exception');
        } elseif ($e instanceof QueryException) {
            if (strpos($e->getMessage(), 'Connection refused') !== false) {
                return $this->view('query-exception--connection-refused');
            } elseif (strpos($e->getMessage(), 'Not null violation') !== false) {
                // TODO
            }
        } elseif ($e instanceof MassAssignmentException) {
            // TODO
        } elseif ($e instanceof UnexpectedValueException) {
            // Logs not writable
            if (str_contains($e->getMessage(), 'failed to open stream') && str_contains($e->getMessage(), 'storage/logs')) {
                return $this->view('unexpected-value-exception--failed-to-open-stream-logs');
            }
        }
        return parent::render($request, $e);
    }

    /**
     * @return string
     */
    private function getModel()
    {
        foreach ($this->exception->getTrace() as $e) {
            if (is_array($e['args'])) {
                foreach ($e['args'] as $arg) {
                    if ($arg instanceof Builder) {
                        return get_class($arg->getModel());
                    }
                }
            }
        }
        return Builder::class;
    }

    /**
     * @param $name
     * @return \Illuminate\Http\Response
     */
    private function view($name)
    {
        return response()->view('indice::' . $name, [
            'e' => $this->exception,
            'errors' => $this->errors,
        ]);
    }
}