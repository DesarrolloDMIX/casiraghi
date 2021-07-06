<?php

use App\Model\Table\ProductsTable;

switch ($level) {
    case 1:
        $url = '/categorias/' . $category->id;
        $productsSectionHeader = 'Todos los productos';
        break;
    case 2:
        $url = '/categorias/' . $gsrCategory->id . '/' . $category->id;
        $productsSectionHeader = 'Todos los productos de <br> ' . $category->name;
        break;
    default:
        $url = '/categorias/';
        $productsSectionHeader = 'Todos los productos de <br>' . $category->name;
        break;
}

$randomProducts = (new ProductsTable())->getRandomProducts();
?>

<section class="section-container">
    <div class="banner-category">
        <img class="banner-category__img" src="<?= $gsrCategory->image_url ?>" alt="<?= $gsrCategory->name ?>">
        <div class="banner-category__img-overlay"></div>
        <h1 class="banner-category__title"><?= $category->name ?></h1>
    </div>
</section>
<section class="section-container section-container--full-width">
    <div class="benefits-list">
        <div class="benefit benefit--flex benefits-list__item">
            <span class="benefit__text">
                <div class="payment-logo payment-logo--pos_visa payment-logo-list__item"></div>
                <div class="payment-logo payment-logo--pos_mastercard payment-logo-list__item"></div>
                <div class="payment-logo payment-logo--pos_cabal payment-logo-list__item"></div>
            </span>
        </div>
        <div class="benefit benefits-list__item">
            <img src="/imgs/c5053eaceb469b727b4dd6c2f4f4bde2.svg" alt="" class="benefit__icon icon" />
            <span class="benefit__text">Pagá con hasta 18 cuotas sin interés</span>
        </div>
        <div class="benefit benefits-list__item">
            <img src="/imgs/619f7eb88366dccd703e0f6c22389a78.svg" alt="" class="benefit__icon icon" />
            <span class="benefit__text">Aceptamos transferencias bancarias</span>
        </div>
    </div>
</section>

<h4 class="section-header">Tipos</h4>
<div class="cards-list js-image-lazy-loader">
    <?php if ($category->child_categories == []) : ?>
        <?php foreach ($category->parent_category->child_categories as $levelCategory) : ?>

            <div class="cards-list__item-container">
                <a href="/categorias/<?= $gsrCategory->id . '/' . $category->parent_category->id . '/' . $levelCategory->id ?>">
                    <div class="card card--secondary <?= $levelCategory->id === $category->id ? 'active' : '' ?>">
                        <div class="card__img">
                            <img data-src="<?= $levelCategory->image_url ?>" alt="<?= $levelCategory->name ?>" />
                        </div>
                        <div class="card__description">
                            <h4 class="card__description--title"><?= $levelCategory->name ?></h4>
                        </div>
                        <span class="cta-button">Ver más</span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <?php foreach ($category->child_categories as $childCategory) : ?>
            <div class="cards-list__item-container">
                <a href="<?= $url . '/' . $childCategory->id ?>">
                    <div class="card card--secondary">
                        <div class="card__img">
                            <img data-src="<?= $childCategory->image_url ?>" alt="<?= $childCategory->name ?>" />
                        </div>
                        <div class="card__description">
                            <h4 class="card__description--title"><?= $childCategory->name ?></h4>
                        </div>
                        <span class="cta-button">Ver más</span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<h4 class="section-header"><?= $productsSectionHeader ?></h4>
<section class="products-section">
    <!-- <div class="products-filter-options">
        <button type="button" style="margin: 20px" class="button-secondary modal-btn" data-toggle="modal" data-target="#exampleModal">
            Filtrar
        </button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body products-filter-options__content">
                        <h2 class="title">Quiero ver:</h2>
                        <ul class="list-of-categories">
                            <?php foreach ($gsrChildren as $key => $gsrChild) : ?>
                                <li class="<?= $gsrChild->id == $category->id ? 'active' : '' ?>">
                                    <h3>
                                        <a href="/categorias/<?= $gsr->id ?>/<?= $gsrChild->id ?>">
                                            <?= $gsrChild->name ?>
                                        </a>
                                    </h3>
                                    <ul>
                                        <?php foreach ($gsrChild->child_categories as $keyGrandChild => $grandChild) : ?>
                                            <li class="<?= $grandChild->id == $category->id ? 'active' : '' ?>">
                                                <a class="<?= $grandChild->id == $category->id ? 'active' : '' ?>" href="/categorias/<?= $gsr->id ?>/<?= $grandChild->id ?>">
                                                    <?= $grandChild->name ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="products-list js-image-lazy-loader">
        <?php if ($products) : ?>
            <?php foreach ($products as $product) : ?>
                <div class="products-list__item-container">
                    <div class="card">
                        <div class="card__img"><img src="<?= $product->default_image->url ?>" alt="carousel" />
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
                            <button class="cta-button js-add-to-cart-button" data-product-id="<?= $product->id ?>" data-fraction="<?= $product->price_fraction ?>" data-cents="<?= $product->price_cents ?>">Comprar</button> <input type="number" name="quantity" value="1">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="crystal-box p-5 text-dark">
                <h3>Lo sentimos!</h3>
                <p>No hay stock de este producto.</p>
            </div>
        <?php endif ?>
        <!-- PRODUCT_LIST__ITEM WITH DISCOUNT -->
        <!-- <div class="products-list__item-container">
        <div class="card">
            <div class="card__img">
                <img src="/img/chapas-1.png" alt="carousel" />
                <div class="card__discount">
                    <span>30% <span class="white-text">OFF</span></span>
                </div>
            </div>
            <div class="card__description">
                <h4 class="card__description--title">Chapas Cincalum de Zinc</h4>
                <p class="card__description--text">
                    Las dimensiones se ajustan a los requerimientos del cliente
                </p>
            </div>
            <div class="price price--with-iva price--with-cents price--with-discounted-price">
                <p class="price__price">
                    $<span class="price__fraction">257</span><span class="price__cents">23</span>
                </p>
                <p class="price__discounted-price">
                    $<span class="price__fraction">257</span><span class="price__cents">23</span>
                </p>
            </div>
            <a href="/productos/1" class="cta-button">Comprar</a>
        </div>
    </div> -->
    </div>
</section>
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
                        <div class="card__img"><img src="<?= $product->default_image->url ?>" alt="carousel" />
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
                            <button class="cta-button js-add-to-cart-button" data-product-id="<?= $product->id ?>" data-fraction="<?= $product->price_fraction ?>" data-cents="<?= $product->price_cents ?>">Comprar</button> <input type="number" name="quantity" value="1">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>