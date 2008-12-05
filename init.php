<?php
require('config.php');

// handler switch
switch($_GET['step'])
{
  case 1: include('clear_handler.php');
    break;
  case 2: include('get_handler.php');
    break;
  case 3: include('search_handler.php');
    break;

  default: include('init_handler.php');
}

// headers
header("Content-Type: text/html; charset=utf-8");
?>