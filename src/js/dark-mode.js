// Function to toggle the Bootstrap theme
function toggleBootstrapTheme() {
    const currentTheme = document.documentElement.getAttribute("data-bs-theme");
    if (currentTheme === "dark") {
        document.documentElement.setAttribute("data-bs-theme", "light");
        document.cookie = "bootstrap-theme=light; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
    } else {
        document.documentElement.setAttribute("data-bs-theme", "dark");
        document.cookie = "bootstrap-theme=dark; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
    }

    // Alterna o ícone com base no tema
    const themeIcon = document.getElementById("theme-icon");
    themeIcon.classList.toggle("fa-moon", currentTheme === "dark");
    themeIcon.classList.toggle("fa-sun", currentTheme !== "dark");
}

// Check for a saved theme preference in a cookie
const savedTheme = document.cookie.replace(/(?:(?:^|.*;\s*)bootstrap-theme\s*=\s*([^;]*).*$)|^.*$/, "$1");
if (savedTheme === "dark") {
    document.documentElement.setAttribute("data-bs-theme", "dark");
} else {
    document.documentElement.setAttribute("data-bs-theme", "light");
}

// Adiciona um evento de clique ao botão de alternância de tema
const themeToggleBtn = document.getElementById("theme-toggle");
themeToggleBtn.addEventListener("click", toggleBootstrapTheme);