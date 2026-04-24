// Intercepta cliques em links de exclusão e exibe confirmação antes de prosseguir
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('a[data-confirm]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            var message = link.getAttribute('data-confirm') || 'Confirmar exclusão?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
