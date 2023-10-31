function capitalizeFirstLetter(input) {
    let valor = input.value;
    valor = valor.charAt(0).toUpperCase() + valor.slice(1);
    input.value = valor;
  }