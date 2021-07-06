<section class="section-container section-container--max-width">
    <div class="panel">
        <h1 class="heading">Crear nueva firma</h1>
        <hr>
        <form action="/admin/crear-firma" method="POST" enctype="multipart/form-data">
            <div class="field">
                <label for="theme" class="field__label">Tema:</label>
                <select name="theme" id="theme">
                    <option value="hnos">Hermanos</option>
                    <option value="express">Express</option>
                </select>
            </div>
            <div class="field">
                <label for="name" class="field__label">Nombre:</label>
                <input type="text" required id="name" name="name" class="field__input">
            </div>
            <div class="field">
                <label for="area" class="field__label">Sector:</label>
                <input type="text" id="area" required name="area" class="field__input">
            </div>
            <div class="field">
                <label for="phone" class="field__label">Teléfono de línea y número interno: (+54 11 4308 0330 al 37 - Int: 000)</label>
                <input type="text" id="phone" required name="phone" class="field__input" value="+54 11 4308 0330 al 37 - Int: 000">
            </div>
            <div class="field">
                <label for="address" class="field__label">Dirección: (Lavardén 537, CABA)</label>
                <input type="text" id="address" required name="address" class="field__input" value="Lavardén 537, CABA">
            </div>
            <div class="field">
                <label for="email" class="field__label">Dirección de correo electrónico:</label>
                <input type="text" id="email" required name="email" class="field__input">
            </div>
            <div class="field">
                <label for="whatsapp" class="field__label">Número de teléfono celular (whatsapp): <b>Opcional</b></label>
                <input type="text" id="whatsapp" name="whatsapp" class="field__input">
            </div>
            <div class="field">
                <label for="pic" class="field__label">Foto: <b>Opcional</b></label>
                <input type="file" id="pic" name="pic" class="field__input">
                <div style="margin-left: 20px;">
                    <p style="margin-top: 5px;">Nota: La foto debe estar recortada circularmente. Visitar <a style="color: blue;" href="https://crop-circle.imageonline.co">crop-circle.imageonline.co</a> para recortar la imagen.</p>
                    <p>El tamaño de la imagen debe ser menor a <?= (int) ini_get('upload_max_filesize') ?>mb.</p>
                </div>
            </div>
            <div class="flex-row" style="margin-top: 40px;">
                <button class="cta-button">Crear</button>
            </div>
        </form>
    </div>
</section>