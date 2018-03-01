# Laravel Indice

## What?

💡 Enlight Laravel errors with hints and code examples.

This package silently turns some default exceptions to a learning-minded error page. 
Because people can forget (or ignore)
how to fix `MethodNotAllowedHttpException` or 
some other semi-obscur Exceptions. This package is for beginners to intermediate developers, tired or memory-less people (just like me).

## Install 

Install (in one step) with composer:

```
composer require rap2hpoutre/indice
```

## Try 

Use your browser to visit a non-existing route. You may see an enlighted error with hints.

## Examples

Here are some screenshots of rendering:

### 🤔 MassAssignmentException

<img width="522" src="https://user-images.githubusercontent.com/1575946/36841326-441d414a-1d48-11e8-9697-ff3c84f02109.png">


### 🤨 MethodNotAllowedHttpException

<img width="595" src="https://user-images.githubusercontent.com/1575946/36841421-8c68328e-1d48-11e8-96a2-28319252c71f.png">

### 😐 Query Exception (connection refused)

<img width="553" src="https://user-images.githubusercontent.com/1575946/36841719-839e90fc-1d49-11e8-91cd-79ca46ccda6d.png">

### 😕 NotFoundHttpException

<img width="492" src="https://user-images.githubusercontent.com/1575946/36841601-1cd3e804-1d49-11e8-8a9c-d31f4af8b8a1.png">
