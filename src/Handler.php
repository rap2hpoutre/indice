<?php

namespace Rap2hpoutre\Indice;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model;
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

        // "IF / ELSEIF / ELSE" HELL.
        if ($e instanceof NotFoundHttpException) {
            return $this->view('not-found-http-exception');
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return $this->view('method-not-allowed-http-exception');
        } elseif ($e instanceof QueryException) {
            if (strpos($e->getMessage(), 'Connection refused') !== false) {
                return $this->view('query-exception--connection-refused');
            } elseif (strpos($e->getMessage(), 'Not null violation') !== false) {
                // return $this->view('query-exception--not-null-violation');
                // TODO
            }
        } elseif ($e instanceof MassAssignmentException) {
            if (strpos($e->getMessage(), 'mass assignment on ') !== false) {
                $model = preg_replace('/^.*\[(.*)\]\.$/', '\\1', $e->getMessage());
                $name = preg_replace('/^[^\[]*\[([^\]]*)\].*$/', '\\1', $e->getMessage());
            } else {
                $model = $this->getModel();
                $name = 'key';
            }
            return $this->view(kebab_case('MassAssignmentException'), [
                'model' => $model,
                'name' => $name
            ]);
        } elseif ($e instanceof UnexpectedValueException) {
            // Logs not writable
            if (str_contains($e->getMessage(), 'failed to open stream') && str_contains($e->getMessage(), 'storage/logs')) {
                return $this->view('unexpected-value-exception--failed-to-open-stream-logs');
            }
        }
        return parent::render($request, $e);
    }

    /**
     * @param string $default
     * @return string
     */
    private function getModel($default = Builder::class)
    {
        foreach ($this->exception->getTrace() as $e) {

            if (is_array($e['args'])) {
                foreach ($e['args'] as $arg) {
                    if ($arg instanceof Builder) {
                        return get_class($arg->getModel());
                    }
                }
            }

            if ($e['class'] ?? '' == Model::class) {
                if ($e['function'] == '__callStatic' && strpos($e['args'][0],  'create') !== false) {
                    // dd(token_get_all(file_get_contents($e['file'])));
                }
            }
        }
        // dd('end');
        return $default;
    }

    /**
     * @param $name
     * @param array $params
     * @return \Illuminate\Http\Response
     */
    private function view($name, $params = [])
    {
        return response()->view('indice::' . $name, array_merge([
            'e' => $this->exception,
            'errors' => $this->errors,
        ], $params));
    }
}