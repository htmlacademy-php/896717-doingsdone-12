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
    $user = get_user($con, $email) ??
    $errors['email'] = 'Такой пользователь не найден';
    $hash = $user['pass'] ?? null;
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }
    if (empty($errors['pass']) && !password_verify($pass, $hash)) {
        $errors['pass'] = 'Неверный пароль';
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
}
$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content ?? false,
        'page_title' => 'Дела в порядке'
    ]
);
print($layout_content);
