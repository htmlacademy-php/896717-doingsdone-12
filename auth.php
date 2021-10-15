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
    $required = ['email', 'pass'];
    $errors = [];
    $email = trim($_POST['email']);
    $pass = $_POST['pass'];
    $user = create_user($con, $email) ?? null;
    $hash = $user['pass'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        } else {
            if (!password_verify($pass, $hash)) {
                $errors['pass'] = 'Неверный пароль';
            }
            if (!check_email($con, $email)) {
                $errors['email'] = 'Такой пользователь не найден';
            }
        }
    }
    if (empty($errors) && $user) {
        $_SESSION['user'] = $user;
        header("Location: /index.php");
        exit();
    } else {
        $page_content = include_template('auth.php', ['errors' => $errors ?? false]);
    }
} else {
    $page_content = include_template('auth.php');
    if (isset($_SESSION['user'])) {
        header("Location: index.php");
        exit();
    }
}
$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content ?? false,
        'page_title' => 'Дела в порядке'
    ]
);
print($layout_content);
