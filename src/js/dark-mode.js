// Função para atualizar o ícone com base no tema atual
function updateThemeIcon() {
    const currentTheme = document.documentElement.getAttribute("data-bs-theme")
    const themeIcon = document.getElementById("theme-icon")
    themeIcon.classList.toggle("fa-sun", currentTheme === "dark")
    themeIcon.classList.toggle("fa-moon", currentTheme !== "dark")
}

// Função para alternar o tema
function toggleBootstrapTheme() {
    const currentTheme = document.documentElement.getAttribute("data-bs-theme")
    if (currentTheme === "dark") {
        document.documentElement.setAttribute("data-bs-theme", "light")
        document.cookie = `bootstrap-theme=${encodeURIComponent("light")} expires=Fri, 31 Dec 9999 23:59:59 GMT path=/`
    } else {
        document.documentElement.setAttribute("data-bs-theme", "dark")
        document.cookie = `bootstrap-theme=${encodeURIComponent("dark")} expires=Fri, 31 Dec 9999 23:59:59 GMT path=/`
    }

    // Chama a função para atualizar o ícone
    updateThemeIcon()
}

// Verifica o tema salvo em um cookie e define o tema da página
const savedTheme = decodeURIComponent(document.cookie.replace(/(?:(?:^|.*\s*)bootstrap-theme\s*=\s*([^]*).*$)|^.*$/, "$1"))
if (savedTheme === "dark") {
    document.documentElement.setAttribute("data-bs-theme", "dark")
} else {
    document.documentElement.setAttribute("data-bs-theme", "light")
}

// Adiciona um evento de clique ao botão de alternância de tema
const themeToggleBtn = document.getElementById("theme-toggle")
themeToggleBtn.addEventListener("click", toggleBootstrapTheme)

// Chama a função para atualizar o ícone ao carregar a página
updateThemeIcon()
