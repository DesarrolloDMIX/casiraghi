<section class="section-container section-container--max-width">
    <div class="panel">
        <form action="/admin/sincro-flexxus" method="POST">
            <div class="flex-row" style="margin-top: 40px;">
                <button class="cta-button" name="sync" value="products">Sincronizar productos</button>
            </div>
        </form>
    </div>
    <div class="panel" style="margin-top: 40px;">
        <form action="/admin/sincro-flexxus" method="POST">
            <div class="flex-row" style="margin-top: 40px;">
                <button class="cta-button" name="sync" value="categories">Sincronizar categorías</button>
            </div>
        </form>
    </div>
    <div class="panel" style="margin-top: 40px;">
        <div class="flex-row"><?= isset($result) ? $result : '' ?></div>
    </div>
</section>