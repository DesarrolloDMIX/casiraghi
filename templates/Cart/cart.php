<div class="cart-wrapper">
    <?php if (isset($errors) && isset($errors['invalid_discount_code'])) : ?>
        <div class="alert alert-danger w-50 ml-auto mr-auto m-2" role="alert">
            El código de descuento no es válido
        </div>
    <?php endif; ?>
    <section class="cart" style="margin-bottom: 100px; margin-top: 100px">
        <?php if (!$empty) : ?>
            <div class="cart__list">
                <?php foreach ($addedProducts as $addedProduct) : ?>
                    <div class="cart-item">
                        <div class="cart-item__column-img">
                            <div class="cart-item__img-container">
                                <img src="<?= $addedProduct['product_entity']->resolveDefaultImageUrl() ?>" class="cart-item__img" />
                            </div>
                        </div>
                        <div class="cart-item__column-details">
                            <div class="cart-item__title">
                                <span><?= $addedProduct['product_entity']->name ?></span>
                            </div>
                            <div class="cart-item__qta">
                                <span>Cantidad:</span><span class="cart-item__qta-number"><?= $addedProduct['quantity'] ?></span>
                            </div>
                            <div class="cart-item__delete-item-container">
                                <a href="/carrito/delete-item/<?= $addedProduct['product_id'] ?>" class="cart-item__delete-item">Eliminar</a>
                            </div>
                            <div class="cart-item__price-container">
                                <div class="price price--with-iva price--with-cents">
                                    <p class="price__price">
                                        $<span class="price__fraction"><?= $addedProduct['product_total_price']['fraction'] ?></span><span class="price__cents"><?= $addedProduct['product_total_price']['cents'] ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart__bottom">
                <div class="cart__summary">
                    <?php foreach ($cartDiscounts as $discount) : ?>
                        <div class="summary-line">
                            <div class="summary-line__qta">
                                <span>Descuento:</span>
                            </div>
                            <div class="summary-line__price-container">
                                <span>- <?= $discount['type'] == 'percent' ? '%' : '$' ?><?= $discount['amount'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="summary-line">
                        <div class="summary-line__qta">
                            <span class="number-products-in-car"><?= $totalQuantity ?></span><span>productos</span>
                        </div>
                        <div class="summary-line__price-container">
                            <span>Total:</span>
                            <div class="price price--with-cents">
                                <p class="price__price">
                                    $<span class="price__fraction"><?= $total['fraction'] ?></span><span class="price__cents"><?= $total['cents'] ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart__actions">
                    <div class="w-100 text-center">
                        <p style="font-size: smaller" class="text-dark m-3">¿Tenés un código de descuento? Ingresalo en el siguiente paso</p>
                    </div>
                    <a href="/#categories-section" type="button" class="button-secondary button-secondary--primary-focus">Seguir comprando</a>
                    <a href="/carrito/to-checkout" type="button" class="cta-button">Finalizar compra</a>
                </div>
                <!-- <button type="button" class="text-dark m-2" class="button-secondary modal-btn" data-toggle="modal" data-target="#discountModal">¿Tenés un código de descuento?</button> 
                    <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body products-filter-options__content">
                                <h2 class="title">Ingresa tu código de descuento:</h2>
                                <form action="/carrito/apply-discount" method="POST">
                                    <input type="hidden" name="_csrfToken" value="<?= $this->request->getAttribute('csrfToken') ?>">
                                    <input type="text" name="code">
                                    <button type="submit" class="cta-button m-3 col-auto">Aplicar descuento</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        <?php else : ?>
            <div class="cart__no-items-message">
                <h3>Todavía no hay ningún producto en tu carrito!</h3>
                <p>
                    Buscá en nuestro
                    <a href="/#categories-section" class="link-to-products">catálogo de productos</a>
                </p>
            </div>
        <?php endif; ?>
    </section>
</div>