<?php

use App\Model\Table\CategoriesTable;
use Wsi\PrestashopApi\Dmix\CategoriesResource;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Wsi\Utils\Cart;

// $branchedCategories = (new CategoriesTable())->getBranchedCategories();
$branchedCategories = (new CategoriesResource())->getBranchedCategories();


/** @var App\View\AppView $this the view */
$cartTotal = Cart::calculateTotal(
    Hash::get($this->request->getCookie('wsi'), 'products_added_to_cart', []),
    Hash::get($this->request->getCookie('wsi'), 'cart_discounts', [])
);

?>
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
    <?= $this->Html->css('banners-6-18-21.css') ?>

    <meta property="og:type" content="article" />

    <meta property="og:title" content="Casiraghi Express" />

    <meta property="og:description" content="Venta minorista de chapa para techo, perfiles, e insumos para la construcción. Directo de fábrica." />

    <meta property="og:image" content="https://express.casiraghi.com.ar/imgs/Casiraghi-express%20iso%20250px.png" />

    <meta property="og:site_name" content="Casiraghi Express" />

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-P6MP4GQ');
    </script>
    <!-- End Google Tag Manager -->
</head>

<body>
    <header>
        <div class="nav__toggler__container">
            <button class="nav__toggler icon icon--with-hover js-nav-toggler">
                <svg width="30px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                    <line x1="18" x2="82" y1="26" y2="26" stroke-linecap="round" stroke-width="10px" stroke="aqua" />
                    <line x1="18" x2="82" y1="50" y2="50" stroke-linecap="round" stroke-width="10px" stroke="aqua" />
                    <line x1="18" x2="82" y1="74" y2="74" stroke-linecap="round" stroke-width="10px" stroke="aqua" />
                </svg>
            </button>
        </div>
        <h1 class="brand">
            <a href="/"><img src="/imgs/5049fbedb734f21d03ea70d47ff4344a.png" alt="Casiraghi Express" /></a>
        </h1>
        <nav class="nav js-nav">
            <ul class="nav__item-list">
                <?php foreach ($branchedCategories as $level1Category) : ?>
                    <?php $level1CategoryId = $level1Category->id ?>
                    <li class="nav__item nav__item--dropdown">
                        <div class="nav__link">
                            <a href="/categorias/<?= $level1CategoryId ?>"><?= $level1Category->name ?></a>
                        </div>
                        <div class="nav__dropdown-container nav__dropdown-container--center">
                            <div class="rubro-grid">
                                <?php foreach ($level1Category->children as $level2Category) : ?>
                                    <ul class="rubro-grid_list rubro-list">
                                        <a href="/categorias/<?= $level2Category->id ?>" class="rubro-list__rubro-name"><?= $level2Category->name ?></a>
                                        <?php foreach ($level2Category->children as $level3Category) : ?>
                                            <li class="rubro-list__product">
                                                <a href="/categorias/<?= $level3Category->id ?>"><?= $level3Category->name ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </li>
                    <div class="nav__dropdown-overlay"></div>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="controls">
            <a href="<?= Configure::read('Prestashop.url') . '/iniciar-sesion' ?>" class="icon icon--with-hover"><img src="/imgs/10d149639b2e54153c3fd19d96a7ce91.png" alt="Perfil" /></a>
            <a href="/carrito" <?= $cartTotal['fraction'] == '0' ? 'data-is-empty' : '' ?> class="icon icon--with-hover cart-button" id="nav-bar-cart">
                <img src="/imgs/93a6c3aeb5bbc06f02675fb065a12b45.png" class="cart-button__icon" alt="Carrito" />
                <span class="cart-button__amount-container">
                    <span class="cart-button__amount-sign">$</span><span class="cart-button__amount-fraction" data-fraction="<?= $cartTotal['fraction'] ?>"><?= $cartTotal['fraction'] ?></span>,<span class="cart-button__amount-cents" data-cents="<?= $cartTotal['cents'] ?>"><?= $cartTotal['cents'] ?></span>
                </span>
                <div class="notice cart-button__notice">
                    <p>Para ver tu carrito y finalizar tu compra hacé click en el ícono del carrito</p>
                </div>
            </a>
        </div>
        <div class="fixed-whatsapp">
            <div class="fixed-whatsapp__img-container">
                <img src="/imgs/d613d3ab4ecff1683e3153b9d827b769.png" alt="" class="fixed-whatsapp__img" />
            </div>
            <div class="fixed-whatsapp__content">
                <span>Escribinos al</span>
                <a href="https://wa.me/5491150579477?text=Hola,%20tengo%20unas%20preguntas" target="_blank">11 5057-9477</a>
                <span>de 8hs a 18hs</span>
            </div>
        </div>
    </header>
    <?= $this->fetch('content') ?>
    <div class="separator separator--no-margin">
        <img src="/imgs/9e12f2a15d3b6ceacc32e19706bc5893.png" alt="Casiraghi" />
    </div>
    <footer class="footer js-footer">
        <div class="footer__map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d28635.185609066106!2d-58.401893308936955!3d-34.64655267912907!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bccb6cace36833%3A0x42d2d1a01b0c516f!2sLavard%C3%A9n%20519%2C%20C1437%20CABA!5e0!3m2!1sen!2sar!4v1590180572326!5m2!1sen!2sar" frameborder="0" style="border: 0;" allowfullscreen="" aria-hidden="false"></iframe>
            <div class="footer__under-map">
                <h4>Dirección</h4>
                <p>Lavardén 519, Ciudad Autónoma de Buenos Aires</p>
                <h4>Horario de atención</h4>
                <p>8:00- 17:00hs de Lunes - Viernes</p>
            </div>
        </div>
        <div class="footer__phones-list-slot">
            <div class="footer__phones-list phones-list">
                <h3 class="phones-list__title">Datos de contacto</h3>
                <p>Atención a empresas 11-4308-0334</p>
                <p>Venta minorista 11-4308-0212</p>
                <p>Conmutador 11-4308-0330</p>
                <p>Fax 11-4308-5381</p>
                <p>e-mail ventas@casiraghi.com.ar</p>
            </div>
        </div>
        <form id="contact" class="footer__contact-form contact-form" method="POST" action="/contacto/enviar-mensaje">
            <h3 class="contact-form__title mt-0 mb-1">Envianos un e-mail</h3>
            <input type="hidden" style="display: none;" name="_csrfToken" value="<?= $this->request->getAttribute('csrfToken') ?>">
            <div class="contact-form__field-set js-contact-form__field-set">
                <div class="flex-row">
                    <div class="field">
                        <label for="name" class="field__label">Nombre</label>
                        <input data-placeholder="Nombre..." class="field__input" name="name" id="name" />
                    </div>
                    <div class="field">
                        <label for="lastName" class="field__label">Apellido</label>
                        <input data-placeholder="Apellido..." class="field__input" name="lastName" id="lastName" />
                    </div>
                </div>
                <div class="flex-row">
                    <div class="field">
                        <label for="email" class="field__label">E-mail</label>
                        <input data-placeholder="Correo electrónico..." type="email" class="field__input" required name="email" id="email" />
                    </div>
                    <div class="field">
                        <label for="phone" class="field__label">Teléfono</label>
                        <input data-placeholder="Teléfono..." class="field__input" name="phone" id="phone" />
                    </div>
                </div>
                <div class="field">
                    <label for="phone" class="field__label">Mensaje</label>
                    <textarea name="message" data-placeholder="Mensaje..." id="message"></textarea>
                </div>
                <div class="flex-row flex-row--h-center">
                    <button type="submit" class="contact-form__button button-secondary color js-contact-form__submit-button">
                        Enviar!
                    </button>
                </div>
            </div>
            <div class="contact-form__sent-screen js-contact-form_sent-screen">
                <div class="contact-form__sent-message">
                    <h3>¡Gracias por contactarnos!</h3>
                    <p>En breve tendrás una respuesta</p>
                </div>
            </div>
        </form>
        <div class="footer__address">
            <p>Lavarden 519, Ciudad Autónoma de Buenos Aires</p>
            <p>8:00 - 17:00hs de Lunes a Viernes</p>
        </div>
        <div class="footer__phone-special phone-special">
            <div class="row mx-0 align-items-center g-1">
                <div class="col col-auto"><img src="/imgs/965d92ce0d9f486938b67ba9fcb04028.png" style="width:40px">
                </div>
                <div class="col">
                    <p class="phone-special__label">WhatsApp</p><a href="https://wa.me/5491150579477?text=Hola,%20tengo%20unas%20preguntas" target="_blank" class="phone-special__number">1150579477</a>
                </div>
            </div>
        </div>
        <div class="footer__water-mark water-mark">
            <img src="/imgs/23ea4654fe961e125be1b27313b8e0a6.png" alt="Casiraghi hermanos" />
        </div>
        <div class="footer__rights">
            <span>© 2020 Casiraghi Hermanos Todos los derechos reservados</span>
        </div>
    </footer>
    <div class="whatsapp-fixed-button">
        <a class="whatsapp-fixed-button__link" href="https://wa.me/541160938590?text=Hola,%20tengo%20unas%20preguntas"></a>
    </div>
    <div class="phone-fixed-button">
        <a class="phone-fixed-button__link" href="tel://1150579477"></a>
    </div>
    <script src="https://live.decidir.com/static/v2.5/decidir.js"></script>
    <script src="/index.bundle.js"></script>
</body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P6MP4GQ" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


</html>