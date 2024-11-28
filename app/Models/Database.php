<?php

namespace Models;

require __DIR__.'/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv;

class Database
{
  function __construct()
  {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
    $dotenv->load();
    $capsule = new Capsule;
    $capsule->addConnection([
      'driver' => 'mysql',
      'host' => $_ENV['DB_HOST'],
      'database' => $_ENV['DB_DATABASE'],
      'username' => $_ENV['DB_USER'],
      'password' => $_ENV['DB_PASS'],
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
    ]);

    $capsule->bootEloquent();
  }
}