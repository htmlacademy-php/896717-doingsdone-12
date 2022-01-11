<?php
/**
 * @var mysqli $con подключение к базе
 */
require_once 'init.php';
require_once 'helpers.php';
require_once 'models.php';

$user = check_user_auth($_SESSION);
if (empty($user)) {
    header('Location: index.php');
    exit;
}
$show_complete_tasks = $_GET['show_completed'] ?? false;
$user = check_user_auth($_SESSION);
$user_id = $user['id'] ?? false;
$categories = get_categories($con, $user_id) ?? false;
if ($user_id) {
    $user_name = $user['name'];
}
$cat_id = $_GET['cat_id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['name', 'category'];
    $errors = [];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }
    $task_name = strip_tags($_POST['name']);
    $task_cat_id = $_POST['category'];
    $task_date = null;
    $task_file = null;
    if (empty($task_name)) {
        $errors['name'] = 'Поле не заполнено';
    }
    if (empty($task_cat_id)) {
        $errors['category'] = 'Категория не выбрана';
    }
    if (!check_category($con, $task_cat_id)) {
        $errors['category'] = 'Выбран несуществующая категория';
    }
    if (!empty($_POST['date'])) {
        $task_date = $_POST['date'];
        if (dates_diff($task_date) < -1) {
            $errors['date'] = 'Дата должна быть не позднее сегодняшней';
        }
    }
    if (($task_date !== null) && ((dates_diff($task_date)) < -1)) {
        $errors['date'] = 'Дата должна быть не позднее сегодняшней';
    }
    if (empty($errors)) {
        if (!empty($_FILES['file']['name'])) {
            $tmp_name = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
            $filename = uniqid() . $filename;
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
            $task_file = $filename;
        }
        insert_task_to_db(
            $con,
            [
                $task_name,
                $task_date,
                $task_cat_id,
                $task_file,
                $user_id
            ]
        );
        header('Location: /index.php');
    } else {
        $page_content = include_template(
            'add.php',
            [
                'errors' => $errors ?? false,
                'categories' => $categories
            ]
        );
    }
} else {
    $page_content = include_template('add.php', ['categories' => $categories]);
}
$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content ?? false,
        'page_title' => 'Дела в порядке',
        'user' => $user ?? false,
        'user_name' => $user_name ?? false
    ]
);
print($layout_content);
