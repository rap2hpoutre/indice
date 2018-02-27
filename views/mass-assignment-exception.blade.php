@extends('indice::layout')

@section('raw-title')
  Model {{$model}} is guarded. Add {{$name ?: 'key'}} to $fillable to allow mass assignment.
@endsection

@section('title')
  Model <code>{{$model}}</code> is guarded.
@endsection

@push('hints')
  <li>
    <p>The Model <code>{{$model ? " $model" : '' }}</code> is fully guarded. <br>It's a defensive behavior:
      Laravel provides by default a protection against <a href="http://laravel.com/docs/eloquent#mass-assignment">mass assignment</a> security issues.
      That's why you have to manually define which fields could be "mass assigned".
    </p>
  </li>
  <li>
    <p>
      Add <code>{{$name ?: 'key'}}</code> to <code>$fillable</code> property
      to allow mass assignment on <code>{{$model}}</code>.
      Edit <code>{{str_replace(['\\', 'App/'], ['/', 'app/'], $model)}}.php</code> file and paste this:
    </p>
    <pre><code class="language-php">protected $fillable = ['{{$name ?: 'key'}}'];</code></pre>
  </li>

  <li>
    You may read <a href="http://laravel.com/docs/eloquent#mass-assignment">this section</a> of Laravel documentation.
  </li>
@endpush