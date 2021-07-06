<section class="section-container section-container--max-width">
    <div class="panel">
        <h1 class="heading">Ingresar contraseña</h1>
        <hr>
        <form action="/admin/login" method="POST">
            <input type="hidden" name="path" value="<?= $this->request->getQuery('path') ?>">
            <!-- <input type="hidden" name="csrfToken" value="<?= $this->request->getAttribute('csrfToken') ?>"> -->
            <div class="field">
                <label for="password" class="field__label">Contraseña:</label>
                <input type="password" id="password" name="password" class="field__input">
            </div>
            <div class="flex-row">
                <button class="cta-button" name="login" value="true">Entrar</button>
            </div>
        </form>
    </div>
</section>