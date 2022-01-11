<?php
/**
 * @var mysqli $con подключение к базе
 */
require_once 'init.php';
require_once 'helpers.php';
require_once 'models.php';
require_once 'vendor/autoload.php';
$transport = new Swift_SmtpTransport("smtp.mailtrap.io", 2525);
$transport->setUsername("75f3c8c888f4c0");
$transport->setPassword("d3bf00f9a2376d");
$mailer = new Swift_Mailer($transport);
$users = get_users_tasks_today($con) ?? false;
$mailing = [];
foreach ($users as $user) {
    $mailing[$user['id']]['user_name'] = $user['task_name'];
    $mailing[$user['id']]['email'] = $user['email'];
    $mailing[$user['id']]['task'][] = [
        'title' => $user['task_name'],
        'deadline' => $user['date']
    ];
}
if (isset($user)) {
    foreach ($mailing as $task) {
        $letter = new Swift_Message();
        $letter->setSubject("Уведомление от сервиса «Дела в порядке»");
        $letter->setFrom('keks@phpdemo.ru');
        $letter->setTo($user['email']);
        $message = 'Уважаемый, ' . $user['user_name'] . '\n';
        foreach ($task['task'] as $task) {
            $message .= 'У вас запланирована задача: ';
            $message .= $task['title'];
            $message .= ' на ' . date('d.m.Y', strtotime($task['deadline']));
            $message .= '\n';
        }
        $letter->addPart($message . '<br>', 'text/html');
        $res = $mailer->send($letter);
        if ($res) {
            print('Рассылка успешно отправлена');
        } else {
            print('Не удалось отправить рассылку');
        }
    }
}
