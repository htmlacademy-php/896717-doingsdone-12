<?php
session_start();
$con = @mysqli_connect("localhost", "mysql", "mysql", "doingsdone");
if (!$con) {
    die("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
}
