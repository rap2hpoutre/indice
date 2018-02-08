@extends('indice::layout')

@section('raw-title')
  The page {{request()->url()}} does not exists
@endsection

@section('title')
  The page <a href="{{request()->url()}}">{{request()->url()}}</a> does not exists
@endsection

@push('hints')
  <li>
  <p>The route does not exists. You could create it by editing <code>routes/web.php</code> and adding this code:</p>
  <pre><code class="language-php">Route::get('{{request()->path()}}', function() {
    return 'hello';
});</code></pre>
  </li>
  <li>
    <p>There may be a typo in route name. Please check if <code>{{request()->path()}}</code> is the right path.</p>
  </li>
@endpush