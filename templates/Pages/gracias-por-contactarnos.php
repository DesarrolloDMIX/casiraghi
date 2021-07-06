<div style="height: calc(100vh - 300px);padding-top: 100px;background: linear-gradient(to bottom, #cecece, rgba(0,0,0,0) 5%),url(/imgs/9754cd420aca7b2fa5f51ac10820e049.jpg) center/cover;" class="thankyou flex-row flex-row--h-center">
    <div>
        <h1 class="heading--xxl">Gracias por contactarnos!</h1>
        <p style="text-align: center;">Te vamos a redirigir en <span data-counter></span> segundo/s</p>
        <script>
            let count = 5;
            let counter = document.querySelector('[data-counter]');
            counter.innerHTML = count;
            setInterval(() => {
                count--;
                if (count <= 0) {
                    window.history.back();
                }
                counter.innerHTML = count;
            }, 1000);
        </script>
    </div>
</div>