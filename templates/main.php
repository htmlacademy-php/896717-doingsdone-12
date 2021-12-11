<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Категории</h2>
        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php if (!empty($categories)) : ?>
                    <?php foreach ($categories as $category) : ?>
                    <li class="main-navigation__list-item <?= (isset($cat_id) && intval($cat_id) === intval($category['id'])) ?
                    'main-navigation__list-item--active' : '' ; ?>">
                        <a class="main-navigation__list-item-link" href="/index.php?cat_id=
                        <?= $category['id'] ; ?>"><?= $category['name'] ; ?></a>
                        <span class="main-navigation__list-item-count">
                            <?= !empty($all_tasks) ? task_сount($all_tasks, $category['id']) : 0; ?>
                        </span>
                    </li>
                    <?php endforeach ; ?>
                <?php endif;?>
            </ul>
        </nav>
        <a class="button button--transparent button--plus content__side-button" href="add_category.php"
        target="project_add">Добавить категорию</a>
    </section>
    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>
        <form class="search-form" action="index.php" method="GET" autocomplete="off">
            <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">
            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>
        <div class="tasks-controls">
            <form class="" action="index.php" method="GET" autocomplete="off">
            <nav class="tasks-switch">
                <button class="tasks-switch__item
                <?= (isset($_GET['tasks-switch']) && $_GET['tasks-switch'] === 'Все задачи')
                ? 'tasks-switch__item--active':'' ?>
                " type="submit" name="tasks-switch" value="Все задачи">Все задачи</button>
                <button class="tasks-switch__item
                <?= (isset($_GET['tasks-switch']) && $_GET['tasks-switch'] === 'Повестка дня')
                ? 'tasks-switch__item--active':'' ?>
                " type="submit" name="tasks-switch" value="Повестка дня">Повестка дня</button>
                <button class="tasks-switch__item
                <?= (isset($_GET['tasks-switch']) && $_GET['tasks-switch'] === 'Завтра')
                ? 'tasks-switch__item--active':'' ?>
                " type="submit" name="tasks-switch" value="Завтра">Завтра</button>
                <button class="tasks-switch__item
                <?= (isset($_GET['tasks-switch']) && $_GET['tasks-switch'] === 'Просроченные')
                ? 'tasks-switch__item--active':'' ?>
                " type="submit" name="tasks-switch" value="Просроченные">Просроченные</button>
            </nav>
            </form>
            <label class="checkbox">
                <input class="checkbox__input visually-hidden show_completed" type="checkbox" name="done"
                <?= (isset($show_complete_tasks) && intval($show_complete_tasks) === 1) ? 'checked' : '' ;?>
                >
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>
        <table class="tasks">
            <?php if (!empty($tasks)) : ?>
                <?php foreach ($tasks as $task) : ?>
                <tr class="tasks__item task
                    <?= (intval($task['done']) === 1) ? 'task--completed' : '' ; ?>
                    <?= (is_deadline($task['date'])) ? 'task--important' : '' ; ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden task__checkbox"
                            type="checkbox" value="<?= ($task['id']) ; ?>">
                            <span class="checkbox__text"><?= mb_strimwidth(($task['name']), 0, 50, '...'); ?></span>
                        </label>
                    </td>

                    <td class="task__file">
                        <?php if (!empty($task['file'])) : ?>
                        <a class="download-link" href="download.php?filename=<?= $task['file']; ?>">Файл</a>
                        <?php endif;?>
                    </td>
                    <td class="task__date"><?= $task['date'] ; ?></td>
                </tr>
                <?php endforeach ; ?>
            <?php else :?>
                <br>
                <p><b>Задачи не найдены</b></p>
            <?php endif;?>
        </table>
    </main>
</div>

