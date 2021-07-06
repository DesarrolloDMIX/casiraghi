<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Casiraghi Express - Home</title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')) ?>

    <link rel="stylesheet" href="/font/font-museo-sans.css" />
    <?= $this->Html->css('styles.css') ?>
    <script src="https://live.decidir.com/static/v2.5/decidir.js"></script>
</head>

<body style="display: flex; flex-direction: column;min-height: 100vh;">
    <header>
        <h1 class="brand">
            <a href="#"><img src="/imgs/5049fbedb734f21d03ea70d47ff4344a.png" alt="Casiraghi Express" /></a>
        </h1>
    </header>
    <div style="flex: 1">
        <?= $this->fetch('content') ?>
    </div>
    <div class="separator separator--no-margin">
        <img src="/imgs/9e12f2a15d3b6ceacc32e19706bc5893.png" alt="Casiraghi" />
    </div>
    <script src="/index.bundle.js"></script>
</body>

</html>