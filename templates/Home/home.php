<?php

// use App\Model\Table\ProductsTable;
use Wsi\PrestashopApi\Dmix\ProductsResource;
use Cake\Core\Configure;

// $randomProducts = (new ProductsTable())->getRandomProducts();
$randomProducts = (new ProductsResource())->getRandomProducts();

?>
<div class="slider js-slider"><button type="button" class="slider__prev"></button>
    <div class="slider__visor">
        <div class="slider__slides-container">
            <div class="slider__slide banner-1">
                <div class="row align-items-center gx-0 justify-content-center text-center">
                    <div class="col-12 col-md-7 d-flex align-items-end align-items-md-center justify-content-center justify-content-md-end h-50 pb-3 pb-md-0 pr-md-4">
                        <h2 class="text-black-50 m-0 text-banner-1">CHAPAS <b>|</b> PERFILES <b>|</b>
                            AISLANTES<br>TORNILLOS <b>|</b> HIERROS <b>|</b> CAÑOS</h2>
                    </div>
                    <div class="col-12 col-md-5 | h-50 pt-1 pb-1 | d-flex justify-content-center justify-content-md-start align-items-start align-items-md-center">
                        <a class="cta-button cta-button--large button-banner-1" href="#categories-section" style="letter-spacing:3px">CATÁLOGO</a>
                    </div>
                </div>
            </div>
            <div class="slider__slide banner-2">
                <div class="row align-items-center gx-0 justify-content-center text-center align-content-center">
                    <div class="col-12 d-flex align-items-end pb-3 justify-content-center">
                        <h2 class="text-white d-flex align-items-center font-weight-normal m-0 text-1-banner-2">Con
                            el respaldo de <img src="/imgs/123c0ce819f88ed148c5cc1e69705234.png" class="logo-banner-2" alt=""></h2>
                    </div>
                    <div class="col-12 | pt-1 pb-1 | d-flex justify-content-center align-items-start text-2-banner-2">
                        <p class="m-0">En alianza estratégica con Ternium, nos dedicamos hace más de 50 años a la
                            producción y comercialización de laminados planos de acero.</p>
                    </div>
                </div>
            </div>
            <div class="slider__slide banner-3">
                <div class="row align-items-center gx-0 align-items-sm-stretch justify-content-center justify-content-md-start align-items-lg-center pt-4 pb-4">
                    <div class="col-10 col-lg-auto d-flex align-items-end align-items-lg-center pb-3 pb-lg-1">
                        <h3 class="text-black-50 font-weight-normal m-0">Capital Federal<br>y Gran Buenos Aires</h3>
                    </div>
                    <div class="col-12 col-lg-auto | pt-1 pb-1 | d-flex align-items-start ml-lg-3">
                        <div class="row align-items-center justify-content-center justify-content-md-start">
                            <div class="col-7 col-md-auto"><a class="cta-button cta-button--large button-banner-3" href="https://express.casiraghi.com.ar/tienda/content/6-zonas-de-envio" target="_blank" style="letter-spacing:3px">ENTREGAS</a></div>
                            <div class="col-3 col-lg-auto"><img class="truck-banner-3" src="/imgs/f6bd76e3af61614289199e98a71a375a.png" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="express-banner-3 d-none d-md-block"><img src="/imgs/046ca33af7ae9ada28293933ca104f76.svg" width="130px" alt=""></div>
            </div>
            <div class="slider__slide banner-4">
                <div class="row align-items-center gx-0 align-items-sm-stretch px-lg-5 justify-content-center justify-content-lg-start align-items-md-center" style="z-index:1">
                    <div class="col-12 col-md-6 col-lg-auto h-50 pb-3 d-flex align-items-end align-items-md-center">
                        <div>
                            <h2 class="m-0 font-weight-bold mb-2 text-1-banner-4">TODO PARA TUS TECHOS</h2>
                            <h3 class="text-white font-weight-light m-0 text-2-banner-4">DIRECTO DE FÁBRICA</h3>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-auto | h-50 pt-1 pb-1 ml-lg-4 | d-flex align-items-start align-items-md-center">
                        <img class="mr-2" style="height:40%;width:auto" src="/imgs/595e822a346ca5028d3876e834d08647.png" alt=""> <img class="mr-2" style="height:40%;width:auto" src="/imgs/f107727dee73c765c2b8b07a3cfe2ec6.png" alt="">
                        <img class="mr-2" style="height:40%;width:auto" src="/imgs/99a190eebce42ae98ec61555bb10b3a1.png" alt="">
                    </div>
                </div>
                <div class="casi-x-banner-4 d-none d-lg-block"><img src="/imgs/618756967e5b5994d43049ae506cb683.svg" alt=""></div>
            </div>
        </div>
    </div><button type="button" class="slider__next"></button>
</div>
<section class="section-container section-container--full-width">
    <div class="benefits-list">
        <div class="benefit benefit--flex benefits-list__item w-100" style="flex-basis:100%"><span class="benefit__text">
                <div class="payment-logo payment-logo--pos_visa payment-logo-list__item"></div>
                <div class="payment-logo payment-logo--pos_mastercard payment-logo-list__item"></div>
                <div class="payment-logo payment-logo--pos_cabal payment-logo-list__item"></div>
                <div class="payment-logo payment-logo--pos_american-express payment-logo-list__item"></div>
            </span></div>
        <div class="benefit benefits-list__item"><img src="/imgs/87c8398af7a6a120018769fe92eea2b7.png" alt="" class="benefit__icon icon" /> <span class="benefit__text">Pagá con ahora 12 y ahora 18</span></div>
        <div class="benefit benefits-list__item"><img src="/imgs/619f7eb88366dccd703e0f6c22389a78.svg" alt="" class="benefit__icon icon" /> <span class="benefit__text">Transferencia bancaria y efectivo</span>
        </div>
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
                        <div class="carousel__card card">
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
                                <input type="number" class="js-product-card__qty-input" min="0" name="quantity" value="1">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- CAROUSEL ITEM WITH DISCOUNTED PRICE -->
                <!-- <div class="carousel__card--container js-carousel-item">
                    <div class="carousel__card card">
                        <div class="card__img">
                            <img src="/img/productos-45.png" alt="carousel" />
                            <div class="card__discount">
                                <span>30% <span class="white-text">OFF</span></span>
                            </div>
                        </div>
                        <div class="card__description">
                            <h4 class="card__description--title">Con chapa laminada</h4>
                            <p class="card__description--text">
                                Las dimensiones se ajustan a los requerimientos del cliente
                            </p>
                        </div>
                        <div class="price price--with-iva price--with-cents price--with-discounted-price">
                            <p class="price__price">
                                $<span class="price__fraction">317</span><span class="price__cents">13</span>
                            </p>
                            <p class="price__discounted-price">
                                $<span class="price__fraction">254</span><span class="price__cents">23</span>
                            </p>
                        </div>
                        <a href="/productos/1" class="cta-button">Comprar</a>
                    </div>
                </div> -->
            </div>
        </div>
    </section>
<?php endif; ?>
<section>
    <div class="banner banner-info">
        <div class="banner-info__content ternium">
            <h2>Centro de servicios</h2>
            <p>
                Proveemos todo tipo de aceros para la industria y la construcción,
                laminados en frío, laminados en caliente, revestidos y prepintados
                que reciben distintos procesos según la necesidad del cliente.
                Tenemos un trayectoria de muchos años pero nuestro foco está puesto
                en los desafíos del futuro
            </p>
            <p>
                Hace mas de 50 años que nos dedicamos a la comercialización de
                laminados de acero, lo que nos permite contar con los más
                altos estándares de calidad del mercado.
            </p>
        </div>
        <div class="banner-info__img-container">
            <img src="/imgs/3b6dbc773e49b797282a10ffdf37822a.png" alt="" class="banner-info__img" />
        </div>
    </div>
</section>
<main id="categories-section" class="categories-main">
    <?php foreach ($categories as $key => $category) : ?>
        <div class="categorie-main<?= !is_int($key / 2) ? ' color' : '' ?>">
            <div class="categorie-main__container-top">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50C100 22.3858 77.6142 0 50 0C22.3858 0 0 22.3858 0 50V100H50H100V50Z" fill="black" />
                </svg>
                <img src="/imgs/981347426e8b4bc570336342046ae59f.png" alt="" class="categorie-main__img-border" />

                <img class="categorie-main__img" src="<?= Configure::read('Prestashop.url') . '/img/c/' . $category->id . '.jpg' ?>" alt="" />
                <a href="/categorias/<?= $category->id ?>" style="position: absolute;top: 0;left: 0;width: 100%; height: 100%"></a>
            </div>
            <div class="categorie-main__container-bottom">
                <div class="categorie-main__text-container">
                    <h2 class="categorie-main__title">
                        <a href="/categorias/<?= $category->id ?>" style="color: inherit"><?= $category->name ?></a>
                    </h2>
                    <p class="categorie-main__description">
                        <?= $category->description  ?>
                    </p>
                    <a href="/categorias/<?= $category->id ?>" class="button-secondary button-secondary--categorie-main-md button-secondary--categorie-main-lg<?= is_int($key / 2) ? ' color' : '' ?>">
                        Artículos
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</main>