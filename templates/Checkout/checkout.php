<section class="checkout-wrapper">
    <form action="" class="checkout-form">
        <div class="fieldset">
            <h4 class="fieldset__title">Datos del comprador</h4>
            <div class="field">
                <label class="field__label" for="first-name">Nombre</label>
                <input class="field__input" id="first-name" name="firstName" />
            </div>
            <div class="field">
                <label class="field__label" for="last-name">Apellido</label>
                <input class="field__input" id="last-name" name="lastName" />
            </div>
            <div class="field">
                <label class="field__label" for="email">E-mail</label>
                <input class="field__input" type="email" id="email" name="email" />
            </div>
            <div class="field">
                <label class="field__label" for="phone">Numero de télefono</label>
                <input class="field__input" id="phone" name="phone" />
            </div>
        </div>
        <div class="fieldset">
            <h4 class="fieldset__title">Dirección</h4>
            <div class="field">
                <label class="field__label" for="adress">Dirección</label>
                <input class="field__input" id="adress" name="adress" />
                <span class="field__help">Ejemplo: Lavardén 519</span>
            </div>
            <div class="field">
                <label class="field__label" for="localidad">Localidad</label>
                <input class="field__input" id="localidad" name="localidad" />
            </div>
            <div class="field">
                <label class="field__label" for="province">Provincia</label>
                <input class="field__input" id="province" name="province" />
            </div>
        </div>
        <div class="fieldset">
            <h4 class="fieldset__title">Método de pago</h4>
            <div class="radio-group">
                <div class="radio-field">
                    <input type="radio" class="radio-field__input" name="paymentMethod" id="payment-method-1" checked="checked" />
                    <label for="payment-method-1" class="radio-field__label">Tarjeta de crédito</label>
                </div>
            </div>
            <div class="payment-method-fieldset" id="payment-method-fieldset-1">
                <div class="field">
                    <label for="credit-card-number" class="field__label">Numero de la tarjeta de crédito</label>
                    <input id="credit-card-number" class="field__input" name="creditCardNumber" />
                </div>
                <div class="group-credit-card-expiry">
                    <div class="field-credit-card-expiry">
                        <label for="credit-card-expiry-month" class="field__label">Mes de vencimiento</label>
                        <input id="credit-card-expiry-month" type="number" class="credit-card-expiry-month" name="creditCardExpiryMonth" />
                    </div>
                    <div class="field-credit-card-expiry">
                        <label for="credit-card-expiry-year" class="field__label">Año de vencimiento</label>
                        <input id="credit-card-expiry-year" type="number" class="credit-card-expiry-year" name="creditCardExpiryYear" />
                    </div>
                    <div class="field-credit-card-expiry">
                        <label for="credit-card-expiry-code" class="field__label">Código de seguridad</label>
                        <input id="credit-card-expiry-code" type="number" class="credit-card-expiry-code" name="creditCardExpiryCode" />
                    </div>
                </div>
            </div>
        </div>
        <div class="checkout-form__button-container">
            <button type="button" class="cta-button">Comprar!</button>
        </div>
    </form>
</section>