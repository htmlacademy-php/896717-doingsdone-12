<?php
/**
 * @var mysqli $con подключение к базе
 */
require_once 'init.php';
require_once 'helpers.php';
require_once 'models.php';

$user = check_user_auth($_SESSION);
if (!empty($user)) {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['email', 'password', 'name'];
    $errors = [];
    $user_name = $_POST['name'];
    $user_mail = $_POST['email'];
    $user_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }
    if (!empty($user_mail)) {
        if (!filter_var($user_mail, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        } elseif (check_email($con, $user_mail)) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }
    if (strlen($user_mail) > 50) {
        $errors['email'] = 'Слишком длинный email';
    }
    if (strlen($user_name) > 50) {
        $errors['name'] = 'Слишком длинное имя';
    }
    if (empty($errors)) {
        insert_user_to_db($con, [$user_name, $user_mail, $user_password]);
        header('Location: /index.php');
    } else {
        $page_content = include_template('register.php', ['errors' => $errors ?? false]);
    }
} else {
    $page_content = include_template(
        'register.php'
    );
}
$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content ?? false,
        'page_title' => 'Регистрация'
    ]
);
print($layout_content);
