@extends('indice::layout')

@section('raw-title')
  SQL Connection refused
@endsection

@section('title')
  SQL Connection refused
@endsection

@push('hints')

  <li>
    <p>You have to configure your database in your <code>.env</code> file (it may be a hidden file):</p>
    <pre><code class="language-bash">DB_CONNECTION=mysql             # Choose: mysql, pgsql or sqlite
DB_HOST=127.0.0.1               # If it does not work, try localhost
DB_PORT=3306                    # use 3306 for MySQL or 5432 for PostgreSQL
DB_DATABASE=your_database_name  # You have to create the database first
DB_USERNAME=your_database_user  # Your SQL user name
DB_PASSWORD=secret              # The SQL user password</code></pre>

  </li>
  <li>
    <p>
      Your current configuration is:
    </p>
    <ul>
      <li>
        Connection: <code>{{config('database.default') }}</code>
      </li>
      <li>
        Host: <code>{{ config('database.connections.' . config('database.default') . '.host') }}</code>
      </li>
      <li>
        Port: <code>{{ config('database.connections.' . config('database.default') . '.port') }}</code>
      </li>
      <li>
        Database: <code>{{ config('database.connections.' . config('database.default') . '.database') }}</code>
      </li>
      <li>
        Username: <code>{{ config('database.connections.' . config('database.default') . '.username') }}</code>
      </li>
      <li>
        Password: <code>{{ config('database.connections.' . config('database.default') . '.password') }}</code>
      </li>
    </ul>

    <br>

  </li>
  <li>
    <p>Check that your SQL server is running.</p>
  </li>
@endpush