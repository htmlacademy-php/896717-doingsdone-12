<div class="content">
    <section class="content__side">
    </section>
    <main class="content__main">
    <h2 class="content__main-heading">Добавление категории</h2>
    <form class="form"  action="add_category.php" method="post" autocomplete="off">
        <div class="form__row">
            <?php $error_classname = !empty($errors['name']) ? "form__input--error" : "";
            $error_text = !empty($errors['name']) ? $errors['name'] : "";?>
            <label class="form__label" for="project_name">Название <sup>*</sup></label>
            <input class="form__input
            <?= $error_classname; ?>" type="text" name="name" id="project_name"
            value="<?= getPostVal('name'); ?>" placeholder="Введите название категории">
            <p class="form__message"><?= $error_text; ?></p>
        </div>
        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
    </main>
</div>
