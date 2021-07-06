<section class="section-container section-container--max-width">
    <div class="panel">
        <h1 class="heading">Subir imagen de firma</h1>
        <hr>
        <form action="/admin/subir-imagen-firma" method="POST" enctype="multipart/form-data">
            <!-- <input type="hidden" name="csrfToken" value="<?= $this->request->getAttribute('csrfToken') ?>"> -->
            <div class="field">
                <label for="image" class="field__label">Imagen:</label>
                <input type="file" id="image" name="image" class="field__input">
            </div>
            <div class="flex-row">
                <button class="cta-button" name="login" value="true">Subir</button>
            </div>
        </form>
    </div>
</section>