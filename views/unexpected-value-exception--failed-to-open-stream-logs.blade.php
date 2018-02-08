@extends('indice::layout')

@section('raw-title')
  The storage/logs directory is not writable.
@endsection

@section('title')
  The <code>storage/logs</code> directory is not writable.
@endsection

@push('hints')
<li>
  <p>
    The <code>storage/logs</code> directory is not writable.
    <a href="https://stackoverflow.com/a/29292637/978690">Learn more about permissions</a>.
  </p>
</li>
<li>
  <p>
This is usually a <a href="https://en.wikipedia.org/wiki/File_system_permissions#Traditional_Unix_permissions">permission</a>
issue caused by different <a href="https://www.tutorialspoint.com/unix/unix-user-administration.htm">users</a> trying to write
at the same log file within the <code>storage/logs</code> folder with different permissions.
  </p>
</li>
  <li>
    <p>
      The <strong>quick and dirty</strong> solution (<strong>use it only in local environment</strong>)
      could be fixing permission
      by allowing every users to read/write/execute:
    </p>
    <pre><code class="language-bash">php artisan cache:clear
chmod -R 666 storage
chmod -R +X storage
composer dump-autoload</code></pre>
  </li>
  <li>
    <p>
      Sometimes, different users can write in <code>storage/logs</code>.
      Think about scheduling tasks, web pages, cli tasks, etc.
      To avoid writing errors with different users, you could add this in <code>bootstrap/app.php</code>:
    </p>
    <pre><code class="language-php">$app->configureMonologUsing(function (Monolog\Logger $monolog) {
    $filename = storage_path('logs/' . php_sapi_name() . '-' . posix_getpwuid(posix_geteuid())['name'] . '.log');
    $monolog->pushHandler($handler = new Monolog\Handler\RotatingFileHandler($filename, 30));
    $handler->setFilenameFormat('laravel-{date}-{filename}', 'Y-m-d');
    $formatter = new \Monolog\Formatter\LineFormatter(null, null, true, true);
    $formatter->includeStacktraces();
    $handler->setFormatter($formatter);
});</code></pre>
    <p>
      Make sure all these users can write into the <code>storage/logs</code> folder.
    </p>
  </li>
@endpush