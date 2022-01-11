<?php
/**
 * @var mysqli $con подключение к базе
 */
require_once 'init.php';
require_once 'helpers.php';
require_once 'models.php';

$user = check_user_auth($_SESSION);
if (!empty($user)) {
    $user_id = $user['id'] ?? null;
    $user_name = isset($user['name']) ? $user['name'] : null;
    $task_checked = isset($_GET['check']) ? intval($_GET['check']) : false;
    $task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : false;
    $show_complete_tasks = $_GET['show_completed'] ?? false;
    $cat_id = $_GET['cat_id'] ?? false;
    $search = $_GET['search'] ?? false;
    $tasks_switch = $_GET['tasks-switch'] ?? false;
    $tasks = get_tasks($con, $user_id, $tasks_switch, $show_complete_tasks) ?? false;
    $all_tasks = get_tasks($con, $user_id, null, null);
    $tasks_by_category = get_tasks_by_category($con, $user_id, $cat_id) ?? false;
    $categories = get_categories($con, $user_id) ?? false;

    $array_page_content = [
            'categories' => $categories,
            'all_tasks' => $all_tasks,
            'tasks' => $tasks,
            'show_complete_tasks' => $show_complete_tasks,
            'cat_id' => $cat_id
        ];

    if ($cat_id) {
        $array_page_content['tasks'] = $tasks_by_category;
        $page_content = include_template('main.php', $array_page_content);
        if (empty($tasks_by_category)) {
            header("HTTP/1.0 404 Not Found");
            $page_content = include_template('404.php');
        }
    } elseif ($search) {
        $array_page_content['tasks'] = get_tasks_by_search($con, [$search]);
        $page_content = include_template('main.php', $array_page_content);
        if (empty(get_tasks_by_search($con, [$search]))) {
            $page_content = include_template('search.php');
        }
    } elseif ($show_complete_tasks && !$tasks_switch) {
        $page_content = include_template('main.php', $array_page_content);
    } elseif ($task_id && $task_checked) {
        task_checkbox($con, $task_id);
        header('Location:/');
        exit;
    } elseif ($tasks_switch) {
        $page_content = include_template('main.php', $array_page_content);
        if (empty($tasks)) {
            $page_content = include_template('main.php', $array_page_content);
        }
    } else {
        $page_content = include_template('main.php', $array_page_content);
    }
    $layout_content = include_template(
        'layout.php',
        [
            'page_content' => $page_content ?? false,
            'page_title' => 'Дела в порядке',
            'user' => $user,
            'user_name' => $user_name
        ]
    );
} else {
    $layout_content = include_template('guest.php');
}

print($layout_content);
