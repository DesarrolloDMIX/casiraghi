<div class="container" style="margin: 20px auto;box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);border-radius: 5px;max-width: 600px;overflow: hidden;">
    <h1 style="margin: 0px 0px 10px;padding: 5px 45px;background-color: #46b6ae;">Pago</h1>
    <div class="content" style="padding: 10px 45px 30px;">
        <h3 style="margin-top: 0px;">El cliente <?= $clientName ?> realizó un pago</h3>
        <div>
            <p>Monto: $<?= $amount ?></p>
            <p>Cuotas: <?= $installments ?></p>
            <p>Email: <?= $clientEmail ?></p>
        </div>
    </div>
</div>