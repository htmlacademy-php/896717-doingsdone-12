<?php
require_once 'helpers.php';

/**
 * Функция возвращает сообщение об ошибке и прерывает скрипт
 *
 * @param string $message сообщение об ошибке
 *
 * @return string возвращает строку с предупреждением
 */
function db_error(string $message)
{
    print("<h2>Ошибка: " . $message . "</h2>");
    exit;
}

/**
* Функция для получения значений из POST-запроса.
*
* @param string $name название поля формы
*
* @return string значение поля формы
*/
function getPostVal(string $name)
{
    if (isset($_POST[$name])) {
        return strip_tags($_POST[$name]) ?? "";
    }
}

/**
* Функция проверяет, есть ли в базе email введенный в форму авторизации
*
* @param mysqli $con подключение к базе
* @param string $email электронный адрес пользователя
*
* @return true или false
*/
function check_email(mysqli $con, string $email)
{
    $sql = 'SELECT id FROM users WHERE email = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$email]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($res) > 0) {
        return true;
    }
}

/**
* Функция проверяет, есть ли в базе id категории введенный в форму добавления
*
* @param mysqli $con подключение к базе
* @param string $category электронный адрес пользователя
*
* @return bool true или false
*/
function check_category(mysqli $con, string $category)
{
    $sql = 'SELECT id FROM category WHERE id = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$category]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($res) > 0) {
        return true;
    }
}

/**
 * Функция добавляет в базу новую задачу
 *
 * @param mysqli $con подключение к базе
 * @param array $data принимает массив с данными
 *
 * @return bool возвращает true/false
 */
function insert_task_to_db(mysqli $con, array $data = [])
{
    $sql = 'INSERT INTO task (name, date, cat_id, file, user_id, dt_add) VALUES (?, ?, ?, ?, ?, NOW())';
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    $res = mysqli_stmt_execute($stmt);
    return $res ?? db_error('Не удалось добавить задачу');
}

/**
 * Функция добавляет в базу нового пользователя
 *
 * @param mysqli $con подключение к базе
 * @param array $data принимает массив с данными
 *
 * @return bool возвращает true/false
 */
function insert_user_to_db(mysqli $con, array $data = [])
{
    $sql = 'INSERT INTO users (name, email, pass, dt_add) VALUES (?, ?, ?, NOW())';
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    $res = mysqli_stmt_execute($stmt);
    return $res ?? db_error('Не удалось добавить пользователя');
}

/**
 * Функция добавляет в базу новую категорию
 *
 * @param mysqli $con подключение к базе
 * @param array $data принимает массив с данными
 *
 * @return bool возвращает true/false
 */
function insert_category_to_db(mysqli $con, array $data = [])
{
    $sql = 'INSERT INTO category (name, user_id) VALUES (?, ?)';
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    $res = mysqli_stmt_execute($stmt);
    return $res ?? db_error('Не удалось добавить категорию');
}

/**
 * Функция получает из базы массив категорий
 *
 * @param mysqli $con подключение к базе
 * @param int $user_id принимает id пользователя
 *
 * @return array массив с категориями
 */
function get_categories(mysqli $con, int $user_id)
{
    $sql = 'SELECT id, name, user_id FROM category WHERE user_id = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : db_error('Не удалось получить массив категорий');
}

/**
 * Функция получает из базы массив задач по выбранной категории
 *
 * @param mysqli $con подключение к базе
 * @param int $user_id принимает id пользователя
 * @param int $cat_id принимает id категории
 *
 * @return array массив задач
 */
function get_tasks_by_category(mysqli $con, int $user_id, int $cat_id)
{
    $sql = 'SELECT id, name, date, cat_id, file, done FROM task WHERE user_id = ? AND cat_id = ? AND done = 0';
    $stmt = db_get_prepare_stmt($con, $sql, [$user_id, $cat_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : db_error('Не удалось получить массив задач');
}

/**
* Функция получает данные из базы и формирует массив данных пользователя
*
* @param mysqli $con подключение к базе
* @param string $email электронный адрес пользователя
*
* @return array массив с данными пользователя или null
*/
function get_user(mysqli $con, string $email)
{
    $sql = 'SELECT id, name, email, pass FROM users WHERE email = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$email]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
}

/**
 * Функция получает из базы массив задач по ключу из поля поиска
 *
 * @param mysqli $con подключение к базе
 * @param array $data принимает id категории
 *
 * @return array массив задач
 */
function get_tasks_by_search(mysqli $con, array $data = [])
{
    $sql = 'SELECT id, name, date, cat_id, file, done FROM task WHERE MATCH(name) AGAINST (?)';
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : db_error('Не удалось получить массив задач');
}

/**
 * Функция получает из базы массив задач в зависимости от переданных параметров
 *
 * @param mysqli $con подключение к базе
 * @param int $user_id айдишник пользователя
 * @param string $tasks_switch
 * @param string $show_complete_tasks
 *
 * @return array массив задач
 */
function get_tasks(mysqli $con, int $user_id, string $tasks_switch = null, string $show_complete_tasks = null)
{
    if ($tasks_switch === 'Повестка дня') {
        $tasks_switch = " AND DATEDIFF(date, NOW()) = 0";
    } else {
        if ($tasks_switch === 'Завтра') {
            $tasks_switch = " AND DATEDIFF(date, NOW()) = 1";
        } else {
            if ($tasks_switch === 'Просроченные') {
                $tasks_switch = " AND DATEDIFF(date, NOW()) < 0";
            } else {
                $tasks_switch = "";
            }
        }
    }
    if ($show_complete_tasks) {
        $sql = "SELECT id, name, date, cat_id, file, done, user_id
        FROM task WHERE user_id = " . $user_id . $tasks_switch;
    } else {
        $sql = "SELECT id, name, date, cat_id, file, done, user_id
        FROM task WHERE done = 0 AND user_id = " . $user_id . $tasks_switch;
    }
    $stmt = mysqli_query($con, $sql);
    $res = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
    return $res ? $res : null;
}

/**
 * Функция меняет значение поле done
 *
 * @param mysqli $con подключение к базе
 * @param int $task_id принимает id задачи
 *
 * @return bool true или false
 */
function task_checkbox(mysqli $con, int $task_id)
{
    $sql = "SELECT done FROM task WHERE id = ?";
    $stmt = db_get_prepare_stmt($con, $sql, [$task_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $res1 = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $result = $res1[0]['done'];
    $sql_arg = null;
    if ($result === 1) {
        $sql_arg = 0;
    } elseif ($result === 0) {
        $sql_arg = 1;
    }
    $sql = "UPDATE task  SET done = ? WHERE id = ?";
    $stmt = db_get_prepare_stmt($con, $sql, [$sql_arg, $task_id]);
    mysqli_stmt_execute($stmt);
    return false;
}

/**
 * Функция получаем задачи пользователей на сегодня
 *
 * @param mysqli $con подключение к базе
 *
 * @return array массив задач
 */
function get_users_tasks_today(mysqli $con)
{
    $sql = "SELECT users.id, users.name as user_name, users.email, task.name as task_name, task.date
    FROM users JOIN task ON task.user_id = users.id
    WHERE task.done = 0 AND task.date = CURRENT_DATE()";
    $stmt = db_get_prepare_stmt($con, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : db_error('Не удалось получить массив задач');
}
