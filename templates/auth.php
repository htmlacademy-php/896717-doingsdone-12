<div class="content">
    <section class="content__side">
    <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>
    <a class="button button--transparent content__side-button" href="auth.php">Войти</a>
    </section>
    <main class="content__main">
        <h2 class="content__main-heading">Вход на сайт</h2>
        <form class="form" action="auth.php" method="post" autocomplete="off">
            <?php $error_classname = !empty($errors['email']) ? "form__input--error" : "";
            $error_text = !empty($errors['email']) ? $errors['email'] : ""; ?>
            <div class="form__row">
                <label class="form__label" for="email">E-mail <sup>*</sup></label>
                <input class="form__input <?= $error_classname; ?>" type="text" name="email" id="email"
                value="<?=getPostVal('email');?>" placeholder="Введите e-mail">
                <p class="form__message"><?= $error_text; ?></p>
            </div>
            <?php $error_classname = !empty($errors['pass']) ? "form__input--error" : "";
            $error_text = !empty($errors['pass']) ? $errors['pass'] : ""; ?>
            <div class="form__row">
                <label class="form__label" for="pass">Пароль <sup>*</sup></label>
                <input class="form__input <?= $error_classname; ?>"
                type="password" name="pass" id="pass" value="" placeholder="Введите пароль">
                <p class="form__message"><?= $error_text; ?></p>
            </div>
            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Войти">
            </div>
        </form>
    </main>
</div>
