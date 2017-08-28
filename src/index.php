<?php
/**
 * start
 *
 * @author   haicheng
 * @datetime 17/2/20 下午3:06
 */
require 'vendor/autoload.php';

ini_set('date.timezone', 'Asia/Shanghai');
defined('ROOT_PATH') or define('ROOT_PATH', __DIR__);

$dotenv = new \Dotenv\Dotenv(ROOT_PATH);
$dotenv->load();

$app = new \Library\Engine();
$app->start();


