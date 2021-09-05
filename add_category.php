<?php
require_once 'helpers.php';
require_once 'init.php';
require_once 'models.php';

$user = check_user_auth($_SESSION);
$user_id = isset($user['id']) ? $user['id'] : false;
if ($user_id) {
    $user_name = $user['name'];
}
$cat_id = $_GET['cat_id'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category_name = htmlspecialchars($_POST['name']);

    if (empty($category_name)) {
        $errors['name'] = 'Поле не заполнено';
    }

    if (empty($errors)) {

        insert_category_to_db($con, [$category_name, $user_id]);

        header('Location: /index.php');

    } else {
        $page_content = include_template(
            'add_category.php',
            [
                'errors' => $errors,
                'categories' => get_categories($con, $user_id),
                'tasks' => get_tasks_by_category($con, $cat_id)
            ]
        );
    }
} else {
    $page_content = include_template(
        'add_category.php',
        [
            'categories' => get_categories($con, $user_id),
            'tasks' => get_tasks_by_category($con, $cat_id, $user_id)
        ]
    );
}

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'page_title' => 'Добавление категории',
        'user' => $user,
        'user_name' => $user_name
    ]
);

print($layout_content);