<?php
namespace Rap2hpoutre\Indice;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Exception;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SuperCustomHandler extends ExceptionHandler
{

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

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
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        $errors = preg_replace('/#([0-9]+)\s+([^(]+)\(([0-9]+)\):\s+(.*)/', '<li><span class="text-muted">\\2</span> line&nbsp;\\3<br>\\4<br><br></li>', $e->getTraceAsString());

        if ($e instanceof NotFoundHttpException) {
            return response()->view('error', [
                'e' => $e,
                'title' => 'The page ' . request()->url() . ' does not exists',
                'errors' => $errors,
                'hints' => [
                    implode("\n", [
                        'The route does not exists. You could create it by editing `routes/web.php` and adding this code:',
                        '',
                        '```php',
                        'Route::get(\'' . request()->path() . '\', function() {',
                        '    return \'hello\';',
                        '});',
                        '```'
                    ]),
                    'There is a typo in route name. Please check if `' . request()->path() . '` is the right path.'
                ]
            ]);
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return response()->view('error', [
                'e' => $e,
                'title' => 'The method ' . request()->method() . ' is not allowed for ' . request()->url(),
                'errors' => $errors,
                'hints' => [
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
                ]
            ]);
        }
        // dd($e);
        return parent::render($request, $e);

    }
}