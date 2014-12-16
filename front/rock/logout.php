<?php
ini_set('display_errors', 'On');
session_start();
session_destroy();
header('location:'.$_GET['url']);