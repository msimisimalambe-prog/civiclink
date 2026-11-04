document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll("form[data-confirm]");
    forms.forEach((form) => {
        form.addEventListener("submit", (event) => {
            const message = form.dataset.confirm;
            if (message && !window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
});
