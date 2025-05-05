document.getElementById('cep').addEventListener('blur', function () {
    let cep = this.value.replace(/\D/g, '');
  
    if (cep.length === 8) {
      fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
          if (!data.erro) {
            document.getElementById('endereco').value = data.logradouro;
            document.getElementById('cidade').value = data.localidade;
            document.getElementById('estado').value = data.uf;
            document.getElementById('bairro').value = data.bairro;
          } else {
            alert('CEP não encontrado.');
          }
        })
        .catch(() => {
          alert('Erro ao buscar o CEP.');
        });
    } else {
      alert('CEP inválido. Deve conter 8 números.');
    }
  });