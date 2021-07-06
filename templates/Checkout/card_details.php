<?php

/** @var \Cake\View\View $this */
?>
<?php if (isset($error)) : ?>

    <div style="height: calc(100vh - 300px);padding-top: 100px;" class="thankyou flex-row flex-row--h-center">
        <div>
            <h1 class="heading--xxl">El token de pago es incorrecto!</h1>
        </div>
    </div>

<?php else : ?>

    <section class="checkout-wrapper">
        <form action="pago" method="POST" class="checkout-form js-form">
            <div class="fieldset">
                <h4 class="fieldset__title">Datos del comprador</h4>
                <div class="field">
                    <label class="field__label" for="name">Nombre completo</label>
                    <input required class="field__input" data-decidir="card_holder_name" id="name" name="name" />
                </div>
                <div class="flex-row flex-row--v-end">
                    <div class="field flex-item--w-2">
                        <label for="id_type" class="field__label">Tipo de documento:</label>
                        <select required class="field__input" data-decidir="card_holder_doc_type" name="id_type" id="id_type">
                            <option value="dni">DNI</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="id_number" class="field__label">Numero:</label>
                        <input required type="number" id="id_number" data-decidir="card_holder_doc_number" name="id_number" class="field__input"></div>
                </div>
            </div>
            <div class="fieldset">
                <h4 class="fieldset__title">Método de pago</h4>
                <div class="radio-group">
                    <div class="radio-field">
                        <input required type="radio" class="radio-field__input" name="paymentMethod" id="payment-method-1" checked="checked" />
                        <label for="payment-method-1" class="radio-field__label">Tarjeta de crédito</label>
                    </div>
                </div>
                <div class="payment-method-fieldset" id="payment-method-fieldset-1">
                    <div class="field">
                        <label for="credit-card-number" class="field__label">Numero de la tarjeta de crédito</label>
                        <input required id="credit-card-number" data-decidir="card_number" class="field__input" name="creditCardNumber" />
                    </div>
                    <div class="group-credit-card-expiry">
                        <div class="field-credit-card-expiry">
                            <label for="credit-card-expiry-month" class="field__label">Mes de vencimiento</label>
                            <input required id="credit-card-expiry-month" data-decidir="card_expiration_month" type="number" class="credit-card-expiry-month" name="creditCardExpiryMonth" />
                        </div>
                        <div class="field-credit-card-expiry">
                            <label for="credit-card-expiry-year" class="field__label">Año de vencimiento</label>
                            <input required id="credit-card-expiry-year" data-decidir="card_expiration_year" type="number" class="credit-card-expiry-year" name="creditCardExpiryYear" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="checkout-form__button-container">
                <button type="submit" class="cta-button">Comprar!</button>
            </div>
            <div class="checkout-form__details panel">
                <h3>Detalles del pago</h3>
                <p>Monto: $<?= (string) $amount ?></p>
                <p>Cuotas: <?= (string) $installments ?></p>
            </div>
        </form>
        <div id="loadingScreen" style="display:none;justify-content:center;align-items:center;position:absolute;top:0;left:0;width:100%;height:100%;background-color: rgba(0,0,0,0.2)">
            <img src="/img/spinning-loader.gif" style="width:200px" alt="">
        </div>
    </section>
    <script>
        let form = document.querySelector('.js-form')

        let decidir = new Decidir('https://developers.decidir.com/api/v2', true)
        decidir.setPublishableKey('96e7f0d36a0648fb9a8dcb50ac06d260')
        decidir.setTimeout(0)

        function handleResponse(status, response) {
            if (status != 200 && status != 201) {
                console.log({
                    error: {
                        status,
                        response
                    }

                })
            } else {
                let data = JSON.stringify(response)
                let formData = new FormData()

                formData.append('jsonData', data)
                formData.append('token', '<?= $token ?>')

                let options = {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-Token': document.querySelector('meta[name="csrfToken"]').getAttribute('content'),
                    }
                }
                fetch('/pagar/link/pago', options)
                    .then(response => response.text())
                    .then(response => {
                        // document.body.innerHTML = response
                        // // console.log(response);
                        let newLocation = `http://express.casiraghi.com.ar/pagar/gracias?email=${response}`
                        window.location = newLocation
                    })
            }
        }

        const submitHandler = (evt) => {
            document.getElementById('loadingScreen').style.display = 'flex'

            evt.preventDefault()
            decidir.createToken(evt.target, handleResponse)
        }

        form.addEventListener('submit', submitHandler);
    </script>

<?php endif; ?>