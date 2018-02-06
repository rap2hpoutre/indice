<?php

namespace Rap2hpoutre\Indice;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Exception;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];
    private $exception;
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
     */
    public function render($request, Exception $e)
    {
        $this->exception = $e;
        $this->errors = preg_replace(
            '/#([0-9]+)\s+([^(]+)\(([0-9]+)\):\s+(.*)/',
            '<li><span class="text-muted">\\2</span> line&nbsp;\\3<br>\\4<br><br></li>',
            $e->getTraceAsString()
        );

        if ($e instanceof NotFoundHttpException) {
            return $this->view('The page ' . request()->url() . ' does not exists', [
                implode("\n", [
                    'The route does not exists. You could create it by editing `routes/web.php` and adding this code:',
                    '',
                    '```php',
                    'Route::get(\'' . request()->path() . '\', function() {',
                    '    return \'hello\';',
                    '});',
                    '```'
                ]),
                'There may be a typo in route name. Please check if `' . request()->path() . '` is the right path.'
            ]);
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return $this->view('The method ' . request()->method() . ' is not allowed for ' . request()->url(), [
                implode("\n", [
                    'There is no route for `/' . request()->path() . '` path via `' . request()->method() . '` method.',
                    'Usually, it means that there is another route for this path, ' .
                    'but with a different [HTTP methods](http://google.com) in `routes/web.php`. ',
                    '',
                    'So there may be something like:',
                    '',
                    '```php',
                    'Route::' . strtolower(explode(', ', $e->getHeaders()['Allow'])[0]) . '(\'' . request()->path() . '\', function() { /* ... */ });',
                    '```',
                    'And you should consider adding:',
                    '',
                    '```php',
                    'Route::' . strtolower(request()->method()) . '(\'' . request()->path() . '\', function() { /* ... */ });\'',
                    '```'
                ]),
                'Allowed [HTTP methods](http://google.com) in your code are: ' . $e->getHeaders()['Allow'] . '.',
                'In short, you could consider adding a new route **or** call an existing route via a valid [HTTP method](http://google.com).',
                'More information [here on StackOverflow](http://google.com).'
            ]);
        } elseif ($e instanceof QueryException) {
            if (strpos($e->getMessage(), 'Connection refused') !== false) {
                return $this->view('SQL Connection refused', [
                    implode("\n", [
                        'You have to configure your database in your `.env` file (it may be a hidden file):',
                        '',
                        '```bash',
                        'DB_CONNECTION=mysql             # Choose: mysql, pgsql or sqlite',
                        'DB_HOST=127.0.0.1               # If it does not work, try localhost',
                        'DB_PORT=3306                    # use 3306 for MySQL or 5432 for PostgreSQL',
                        'DB_DATABASE=your_database_name  # You have to create the database first',
                        'DB_USERNAME=your_database_user  # Your SQL user name',
                        'DB_PASSWORD=secret              # The SQL user password',
                        '```'
                    ]),
                    implode("\n", [
                        'Your current configuration is: ',
                        '- Connection: `' . config('database.default') . '`',
                        '- Host: `' . config('database.connections.' . config('database.default') . '.host') . '`',
                        '- Port: `' . config('database.connections.' . config('database.default') . '.port') . '`',
                        '- Database: `' . config('database.connections.' . config('database.default') . '.database') . '`',
                        '- Username: `' . config('database.connections.' . config('database.default') . '.username') . '`',
                        '- Password: `' . config('database.connections.' . config('database.default') . '.password') . '`',
                        '<br>'
                    ]),
                    'Check that your SQL server is running.',
                ]);
            }

            dd($this->getModel());

        } elseif ($e instanceof MassAssignmentException) {

        }
        return parent::render($request, $e);
    }

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

    private function view($title, $hints)
    {
        return response()->view('indice::default', [
            'e' => $this->exception,
            'title' => $title,
            'errors' => $this->errors,
            'hints' => $hints
        ]);
    }
}