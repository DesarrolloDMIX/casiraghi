<?php

/** @var \Cake\View\View $this */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')) ?>
    <title>test decidir</title>
    <script src="https://live.decidir.com/static/v2.5/decidir.js"></script>
</head>

<body>

    <h1>Test decidir</h1>
    <form id="form">
        <div class="field">
            <input type="text" data-decidir="card_holder_name" value="nombre falso" class="field__input">
        </div>
        <div class="field">
            <input type="number" data-decidir="card_number" value="4507990000004905" class="field__input">
        </div>
        <div class="field">
            <input type="number" data-decidir="card_expiration_month" value="12" value="" class="field__input">
        </div>
        <div class="field">
            <input type="text" data-decidir="card_expiration_year" value="22" class="field__input">
        </div>
        <div class="field">
            <select data-decidir="card_holder_doc_type">
                <option value="dni">DNI</option>
            </select>
        </div>
        <div class="field">
            <input type="text" data-decidir="card_holder_doc_number" placeholder="XXXXXXXXXX" value="27666328" />
        </div>
    </form>

    <script>
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

                let options = {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-Token': document.querySelector('meta[name="csrfToken"]').getAttribute('content'),
                    }
                }
                fetch('/test-decidir/execute-payment', options)
                    .then(response => response.text())
                    .then(response => {
                        document.body.innerHTML = response
                    })
            }
        }

        decidir.createToken(document.getElementById('form'), handleResponse)
    </script>

</body>

</html>