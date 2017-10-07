<?php
/*--------------------------------------------------
|
|   $builder->configure
|   $builder->variables
|   Usage (it's the same):
|
|   One variable:
|   $builder->configure('bitch', 'yes?');
|
|   Multiple variables : 
|   $builder->configure([
|       'bitch' => 'yes?',
|       'debug' => true
|   ]);
|
|---------------------------------------------------
|
|   Custom code usage:
|   { $ VAR } (set by $builder->variables())
|   { @include FILENAME } (without .html)
|
\-------------------------------------------------*/
require_once('template.class.php');

$builder->configure('debug', true);

$builder->variables([
    'title' => 'Title',
    'text' => 'Hello world!'
]);

$builder->render('test');
?>