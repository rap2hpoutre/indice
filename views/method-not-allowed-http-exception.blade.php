@extends('indice::layout')

@section('raw-title')
  The method {{request()->method()}} is not allowed for {{ request()->url()}}
@endsection

@section('title')
  The method <code>{{request()->method()}}</code> is not allowed for <a href="{{ request()->url()}}">{{ request()->url()}}</a>
@endsection

@push('hints')
  <li>
    <p>There is no route for <code>/{{ request()->path() }}</code>
      path via <code>{{ request()->method() }}</code> method.<br>
      Usually, it means that there is another route for this path,
      but with a different <a href="">HTTP methods</a> in <code>routes/web.php</code>
    </p>
    <p>So there may be something like:</p>
    <pre><code class="language-php">Route::{{strtolower(explode(', ', $e->getHeaders()['Allow'])[0])}}('{{request()->path()}}', function() { /* ... */ });</code></pre>
    <p>And you should consider adding:</p>
    <pre><code class="language-php">Route::{{strtolower(request()->method())}}('{{request()->path()}}', function() { /* ... */ });</code></pre>
  </li>
  <li>
    <p>Allowed <a href="">HTTP methods</a> in your code are: {{$e->getHeaders()['Allow']}}.</p>
  </li>
  <li>
    <p>In short, you could consider adding a new route <strong>or</strong> call an existing route via a valid <a href="">HTTP method</a>.</p>
  </li>
  <li><p>More information <a href="">here on StackOverflow</a>.</p></li>
@endpush