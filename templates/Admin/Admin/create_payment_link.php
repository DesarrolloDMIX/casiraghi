<section class="section-container section-container--max-width">
    <div class="panel">
        <h1 class="heading">Enviar link de pago</h1>
        <hr>
        <form action="" method="POST">
            <div class="field">
                <label for="email" class="field__label">Email del cliente:</label>
                <input type="email" id="email" name="email" class="field__input">
            </div>
            <div class="field">
                <label for="amount" class="field__label">Precio:</label>
                <input type="text" id="amount" name="amount" class="field__input">
            </div>
            <div class="flex-row">
                <div class="field">
                    <label for="card_type" class="field__label">Tipo de tarjeta</label>
                    <select type="text" id="card_type" name="card_type" class="field__input">
                        <option value="1">Visa</option>
                    </select>
                </div>
                <div class="field flex-item--w-2">
                    <label for="installments" class="field__label">Cuotas:</label>
                    <input type="number" id="installments" name="installments" class="field__input">
                </div>
            </div>
            <div class="flex-row">
                <button class="cta-button" name="create_link" value="true">Enviar link</button>
            </div>
        </form>
        <?php if (isset($success)) : ?>
            <?php if ($success) : ?>
                <div class="success"><span>Se envió el enlace al cliente!</span></div>
            <?php else : ?>
                <div class="error"><span>Oups! hubo un error :(</span></div>
            <?php endif ?>
        <?php endif ?>
    </div>
</section>