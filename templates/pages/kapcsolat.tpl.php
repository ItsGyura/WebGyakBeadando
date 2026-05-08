<main>
    <section class="tablazat contact-card">
        <h2>Kapcsolat</h2>
        <p>Írjon üzenetet az oldal tulajdonosának. </p>

        <p id="contact-error" class="auth-message" aria-live="polite"></p>

        <form id="contact-form" action="uzenetek" method="post">
            <label for="targy">Tárgy:</label>
            <input type="text" id="targy" name="targy">

            <label for="uzenet">Üzenet:</label>
            <textarea id="uzenet" name="uzenet" rows="6"></textarea>

            <button type="submit" class="gomb">Üzenet küldése</button>
        </form>
    </section>
</main>

<script>
(function () {
    const form = document.getElementById('contact-form');
    const errorBox = document.getElementById('contact-error');

    const showError = (message) => {
        errorBox.textContent = message;
    };

    form.addEventListener('submit', function (event) {
        const targy = document.getElementById('targy').value.trim();
        const uzenet = document.getElementById('uzenet').value.trim();

        const errors = [];

        if (targy.length < 3) {
            errors.push('A tárgy legalább 3 karakter legyen.');
        }

       if (uzenet.length < 10) {
            errors.push('Az üzenet legalább 10 karakter legyen.');
        }


        if (errors.length > 0) {
            event.preventDefault();
            showError(errors.join(' '));
        } else {
            errorBox.textContent = '';
        }
    });
})();
</script>