<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/atom-one-light.min.css" />
  <script src=" https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.8.6/showdown.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/php.min.js"></script>
  <title>@yield('title', 'Exception found!')</title>
</head>
<body>
<div id="app" class="container mt-4">
  <div id="title" class="h3">{{$title}}</div>
  <hr>
  <h5>Hints</h5>
  <ol id="hints">
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
  // Ugly JS, sorry.
  var hints = {!! json_encode($hints) !!};
  var converter = new showdown.Converter();
  converter.setFlavor('github');
  var hints_div = document.getElementById('hints');
  document.getElementById('title').innerHTML = converter.makeHtml('{{$title}}');
  for (var i in hints) {
    var newNode = document.createElement('li');
    newNode.innerHTML = converter.makeHtml(hints[i]);
    hints_div.appendChild( newNode );
  }
  hljs.initHighlightingOnLoad();
</script>
</body>
</html>