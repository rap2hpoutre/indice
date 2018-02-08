<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/atom-one-light.min.css" />
  <script src=" https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/php.min.js"></script>
  <title>@yield('raw-title', get_class($e) . ' - ' . $e->getMessage())</title>
</head>
<body>
<div id="app" class="container mt-4">
  <div id="title" class="h3">@yield('title', get_class($e) . ' - ' . $e->getMessage())</div>
  <hr>
  <h5>Hints</h5>
  <ol id="hints">
    @stack('hints')
  </ol>
  <hr>
  <h5>Type</h5>
  <code class="ml-4 php language-php">{{get_class($e)}}</code>
  <hr>
  @if ($e->getMessage())
    <h5>Message</h5>
    <code class="ml-4 php language-php">{{$e->getMessage()}}</code>
    <hr>
  @endif
  <h5>Stack Trace</h5>
  <ol>{!! $errors !!}</ol>
</div>
<script>
  hljs.initHighlightingOnLoad();
</script>
</body>
</html>