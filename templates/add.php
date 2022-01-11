<div class="content">
    <section class="content__side">
    </section>
    <main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>
        <form class="form" action="add.php" method="post"
        autocomplete="off" enctype="multipart/form-data">
            <div class="form__row">
                <?php $error_classname = !empty($errors['name']) ?
                "form__input--error" : "";
                $error_text = !empty($errors['name']) ? $errors['name'] : ""; ?>
                <label class="form__label" for="name">Название <sup>*</sup></label>
                <input class="form__input
            <?php echo $error_classname; ?>" type="text" name="name" id="name"
                       value="<?php echo getPostVal('name'); ?>"
                       placeholder="Введите название">
                <p class="form__message"><?php echo $error_text; ?></p>
            </div>
            <div class="form__row">
                <?php $error_classname = !empty($errors['category']) ?
                "form__input--error" : ""; $error_text = !empty($errors['category'])
                ? $errors['category'] : "";
                ?>
                <label class="form__label" for="category">
                    Категория <sup>*</sup></label>
                <select class="form__input form__input--select" name="category"
                id="category">
                    <option value="">Выберете категорию</option>
                    <?php if (isset($categories)) : ?>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p class="form__message"><?php echo $error_text; ?></p>
            </div>
            <div class="form__row">
                <?php $error_classname = !empty($errors['date']) ?
                "form__input--error" : ""; ?>
                <?php $error_text = !empty($errors['date']) ?
                $errors['date'] : ""; ?>
                <label class="form__label" for="date">Дата выполнения</label>
                <input class="form__input form__input--date
            <?php echo $error_classname; ?>" type="text" name="date" id="date"
                       value="<?php echo getPostVal('date'); ?>"
                       placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                <p class="form__message"><?php echo $error_text; ?></p>
            </div>
            <div class="form__row">
                <?php $error_classname = !empty($errors['file']) ?
                "form__input--error" : ""; ?>
                <?php $error_text = !empty($errors['file']) ?
                $errors['file'] : ""; ?>
                <label class="form__label" for="file">Файл</label>
                <div class="form__input-file">
                    <input class="visually-hidden" type="file" name="file" id="file"
                    value="<?php echo getPostVal('file'); ?>">
                    <label class="button button--transparent" for="file">
                        <span>Выберите файл</span>
                    </label>
                </div>
                <p class="form__message"><?php echo $error_text; ?></p>
            </div>
            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>
    </main>
</div>

