function formatarCpf(campo) {
    campo.value = campo.value.replace(/\D/g, '') // Remove tudo o que não é dígito
    campo.value = campo.value.replace(/(\d{3})(\d)/, '$1.$2') // Adiciona o primeiro ponto
    campo.value = campo.value.replace(/(\d{3})(\d)/, '$1.$2') // Adiciona o segundo ponto
    campo.value = campo.value.replace(/(\d{3})(\d{1,2})$/, '$1-$2') // Adiciona o traço
}