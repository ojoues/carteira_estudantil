// Configure a codificação UTF-8 para o JavaScript
document.characterSet = 'UTF-8'
    
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.confirm-delete')

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault()
            
            const nome = button.getAttribute('data-nome')
            const userId = button.getAttribute('data-user-id')
            
            const confirmation = confirm(`Você tem certeza que deseja apagar ${nome || userId}?`)
            
            if (confirmation) {
                // Redirecionar para a URL de exclusão após a confirmação
                window.location.href = button.getAttribute('href')
            }
        })
    })
})