<?php

// use App\Model\Table\ProductsTable;

use Cake\Core\Configure;
use Wsi\PrestashopApi\Dmix\ProductsResource;

$firstImageKey = array_key_first($product->resolveImagesUrls());

// $randomProducts = (new ProductsTable())->getRandomProducts();
$randomProducts = (new ProductsResource())->getRandomProducts();

?>

<section class="product-with-details">
    <h1 class="product-with-details__title product-card__title">
        <?= $product->name ?>
    </h1>
    <div class="product-with-details__img-container product-card__img-container js-product-card__img-container">
        <img class="product-with-details__img product-card__img js-product-card__img" src="<?= $product->resolveImagesUrls()[$firstImageKey] ?>" alt="<?= $product->name ?>" data-current-index="<?= $firstImageKey ?>" />
        <div class="product-card__img-overlay"></div>
        <button class="product-card__img-selector icon icon--with-hover product-card__img-selector-prev js-product-card__img-selector-prev">
            <img src="/imgs/e69630ed3a521d0b351b97d8c4b5c682.svg" alt="arrow" />
        </button>
        <button class="product-card__img-selector icon icon--with-hover product-card__img-selector-next js-product-card__img-selector-next">
            <img src="/imgs/e69630ed3a521d0b351b97d8c4b5c682.svg" alt="arrow" />
        </button>
        <ul style="display:none;" class="product-with-details__colors-list product-card__colors-list js-product-card__colors-list">
            <li class="js-product-card__colors-list-item" data-color="#f1f1f1" data-color-id="1"></li>
            <li class="js-product-card__colors-list-item" data-color="#f1f1f1" data-color-id="2"></li>
            <li class="js-product-card__colors-list-item" data-color="#f1f1f1" data-color-id="3"></li>
            <li class="js-product-card__colors-list-item" data-color="#f1f1f1" data-color-id="4"></li>
            <li class="js-product-card__colors-list-item" data-color="#f1f1f1" data-color-id="5"></li>
            <li class="js-product-card__colors-list-item" data-color="#33ff00" data-color-id="6"></li>
            <div class="error-message"><span>Elegí un color!</span></div>
        </ul>
    </div>
    <div class="product-with-details__img-selector-list js-product-card__img-selector-list">
        <?php foreach ($product->resolveImagesUrls() as $key => $image) : ?>
            <img src="<?= $image ?>" alt="" class="
                    <?= $key == $firstImageKey ? 'active' : '' ?> 
                    product-card__img-selector-list-item 
                    js-product-card__img-selector-list-item" data-new-index="<?= $key ?>" />
        <?php endforeach; ?>
    </div>
    <div class="product-with-details__controls-container product-card__bottom js-product-card__bottom">
        <div class="product-card__price-container">
            <div class="price price--with-iva price--with-cents">
                <p class="price__price">
                    $<span class="price__fraction"><?= $product->price_fraction ?></span><span class="price__cents"><?= $product->price_cents ?></span>
                </p>
            </div>
        </div>
        <div class="product-with-details__controls product-card__controls">
            <div class="input-field">
                <label for="product-amount-1">Cantidad</label>
                <input min="1" value="1" type="number" name="product-amount" id="product-amount-1" class="js-product-card__qty-input" />
            </div>
            <button data-product-id="<?= $product->id ?>" data-fraction="<?= $product->price_fraction ?>" data-cents="<?= $product->price_cents ?>" class="cta-button js-product-card__add-to-cart-button">
                AGREGAR AL CARRITO
            </button>
            <button class="cta-button js-product-card__finish-shopping-button">
                FINALIZAR COMPRA
            </button>
        </div>
        <div class="product-card__added-to-cart-screen js-product-card__added-to-cart-screen">
            <p>Agregaste este producto a tu carrito</p>
        </div>
    </div>
    <div class="product-with-details__promo">
        <div class="product-with-details__promo-item"><span>Pagá en hasta 18 cuotas</span>
            <div class="payment-logo-list">
                <div class="payment-logo payment-logo--small payment-logo--pos_visa payment-logo-list__item"></div>
                <div class="payment-logo payment-logo--small payment-logo--pos_visa-debito payment-logo-list__item"></div>
                <div class="payment-logo payment-logo--small payment-logo--pos_mastercard payment-logo-list__item"></div>
                <div class="payment-logo payment-logo--small payment-logo--pos_mastercard-debito payment-logo-list__item"></div>
                <div class="payment-logo payment-logo--small payment-logo--pos_cabal payment-logo-list__item"></div>
                <div class="payment-logo payment-logo--small payment-logo--pos_american-express payment-logo-list__item"></div>
            </div>
        </div>
    </div>
    <div class="product-with-details__promo">
        <div class="product-with-details__promo-item">
            <div class="row justify-content-center">
                <div class="col-2"><img src="/imgs/3a97c0a19c30139bc99b141a9bf8d339.png"></div>
                <div class="col-2"><img src="/imgs/1efdc409e4bc6780e617d1059876873b.png"></div>
            </div>
        </div>
    </div>
    <div class="product-with-details__benefits benefits-list">
        <div class="benefit benefit--dark benefits-list__item">
            <img src="/imgs/af154b1fc2ffdfc4d67bf986e5232848.png" alt="" class="benefit__icon icon" />
            <span class="benefit__text">Te lo llevamos a donde necesites</span>
        </div>
        <div class="benefit benefit--dark benefits-list__item">
            <img src="/imgs/c5053eaceb469b727b4dd6c2f4f4bde2.svg" alt="" class="benefit__icon icon" />
            <span class="benefit__text">Pagá con hasta 18 cuotas sin interés</span>
        </div>
        <div class="benefit benefit--dark benefits-list__item">
            <img src="/imgs/1b156e6c2aad32e77b1edeb19b646216.svg" alt="" class="benefit__icon icon" />
            <span class="benefit__text">Siempre con stock disponible</span>
        </div>
    </div>
    <?php if (in_array(($product->category_id ?? $product->default_category_id), Configure::read('Prestashop.categories_ternium_ids'))) : ?>
        <div class="row justify-content-center product-with-details__promo mx-0">
            <div class="col-2"><img src="/imgs/99a190eebce42ae98ec61555bb10b3a1.png" alt=""></div>
        </div>
    <?php endif; ?>
    <div class="product-with-details__details product-details">

        <div class="product-details__row">
            <p>
                <?= $product->description_short != null ? $product->description_short : "" ?>
            </p>
        </div>
        <div class="product-details__row product-details__row--lighter">
            <p>
                <?= $product->description != null ? $product->description : "" ?>
            </p>
        </div>
    </div>
</section>
<div class="advice-button-container">
    <button style="display:none" type="button" class="button-secondary button-secondary--thicker button-secondary--primary-focus">
        Solicitar un asesor
    </button>
</div>
<section class="graph-list">
    <div class="graph">
        <img src="/imgs/af0e2b9ff18986f7c4f3d1931967ecad.png" alt="graph 1" />
    </div>
    <div class="graph">
        <img src="/imgs/8927cadefb17762d32e124970ed384fc.png" alt="graph 2" />
    </div>
</section>
<?php if ($randomProducts) : ?>
    <section class="carousel__container">
        <h4 class="carousel__header">Productos destacados</h4>
        <div class="carousel js-carousel">
            <button class="carousel__control icon icon--with-hover carousel__prev js-carousel-prev">
                <img src="/imgs/e69630ed3a521d0b351b97d8c4b5c682.svg" alt="arrow" />
            </button>
            <button class="carousel__control icon icon--with-hover carousel__next js-carousel-next">
                <img src="/imgs/e69630ed3a521d0b351b97d8c4b5c682.svg" alt="arrow" />
            </button>
            <div data-position="0" class="carousel__track js-carousel-track">
                <?php foreach ($randomProducts as $product) : ?>
                    <div class="carousel__card--container js-carousel-item">
                        <div class="card">
                            <div class="card__img"><img src="<?= $product->resolveDefaultImageUrl() ?>" alt="carousel" />
                            </div>
                            <div class="card__description">
                                <h4 class="card__description--title"><?= $product->name ?></h4>
                            </div>
                            <div class="price price--with-iva price--with-cents">
                                <p class="price__price">$<span class="price__fraction"><?= $product->price_fraction ?></span><span class="price__cents"><?= $product->price_cents ?></span></p>
                            </div><a href="/productos/<?= $product->id ?>" class="card__link-overlay"></a> <button class="cta-button card__see-more-button">Ver más</button>
                            <div class="card__add-to-cart-field">
                                <div class="card__add-to-cart-success-message">
                                    <p>Agregaste este producto a tu carrito</p>
                                </div>
                                <button class="cta-button js-add-to-cart-button" data-product-id="<?= $product->id ?>" data-fraction="<?= $product->price_fraction ?>" data-cents="<?= $product->price_cents ?>">Comprar</button>
                                <input type="number" name="quantity" class="js-product-card__qty-input" min="1" value="1">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<section class="features-list">
    <div class="feature">
        <h2 class="feature__title">Cortes a medida</h2>
        <img class="feature__img" src="/imgs/e749340e66015fcf73da8516e75f8051.png" alt="Cortes a medida" />
        <p class="feature__text">
            Ahorrá en tiempos de cortes y en desperdicios. Conformamos y cortamos
            exactamente la cantidad y las medidas que necesitás.
        </p>
    </div>
    <div class="feature">
        <h2 class="feature__title">Gran capacidad de producción</h2>
        <img class="feature__img" src="/imgs/51ce0ab2ddc4b7d612ae778718df328a.png" alt="Gran capacidad de producción" />
        <p class="feature__text">
            Contamos con amplio stock y maquinarias propias, lo que nos permite
            cumplir en tiempo y forma con los requerimientos de nuestros clientes.
        </p>
    </div>
    <div class="feature">
        <h2 class="feature__title">Logística</h2>
        <img class="feature__img" src="/imgs/9f262558f0956636b26f56747ccb6d45.png" alt="Logística" />
        <p class="feature__text">
            Contamos con una flota de camiones propios. Esto nos habilita a dar
            flexibilidad en entregas, atender urgencias y proveer un servicio
            personalizado.
        </p>
    </div>
    <div class="feature">
        <h2 class="feature__title">Planta</h2>
        <img class="feature__img" src="/imgs/91e8c26ddda55ac49216109207cb8a9b.png" alt="Plata" />
        <p class="feature__text">
            Con 7.000 metros cuadrados de superficie, nuestra planta está equipada
            con la maquinaria necesaria para proveer entregas a medida para
            cualquier tipo de necesidad.
        </p>
    </div>
</section>