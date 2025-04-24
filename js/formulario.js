// js/formulario.js

document.addEventListener('DOMContentLoaded', () => {
    const form     = document.getElementById('formCadastro');
    const cepInput = document.getElementById('cep');
  
    // 1) Busca endereço via ViaCEP
    if (cepInput) {
      cepInput.addEventListener('blur', () => {
        const cep = cepInput.value.replace(/\D/g, '');
        if (cep.length === 8) {
          fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(r => r.json())
            .then(data => {
              if (!data.erro) {
                document.getElementById('endereco').value = data.logradouro || '';
                document.getElementById('bairro')  .value = data.bairro    || '';
                document.getElementById('cidade')  .value = data.localidade|| '';
                document.getElementById('uf')      .value = data.uf        || '';
              }
            })
            .catch(() => alert('Erro ao buscar CEP. Verifique sua conexão.'));
        }
      });
    }
  
    if (!form) {
      console.warn('formCadastro não encontrado — verifique o id do <form>');
      return;
    }
  
    // 2) Validação geral no submit
    form.addEventListener('submit', e => {
      const msgs = [];
  
      // 2.1) Todos os campos text/select com [required]
      form.querySelectorAll('[required]').forEach(f => {
        if (!f.value.trim()) {
          f.style.borderColor = 'red';
          msgs.push(`Campo obrigató­rio: "${f.previousElementSibling.innerText}".`);
        } else {
          f.style.borderColor = '';
        }
      });
  
      // 2.2) Validações específicas
      const nome    = document.getElementById('nome');
      if (nome && !validarNomeCompleto(nome.value)) {
        nome.style.borderColor = 'red';
        msgs.push('Informe nome completo (mínimo 2 palavras, sem números).');
      }
  
      const rg = document.getElementById('rg');
      if (rg && !validarRG(rg.value)) {
        rg.style.borderColor = 'red';
        msgs.push('RG inválido (mínimo 7 dígitos).');
      }
  
      const emissor = document.getElementById('emissor');
      if (emissor && !validarEmissor(emissor.value)) {
        emissor.style.borderColor = 'red';
        msgs.push('Órgão emissor inválido (2–10 letras maiúsculas).');
      }
  
      if (!validarRenda()) {
        msgs.push('Renda inválida (máx R$ 20.000,00 e ≠ 0).');
      }
  
      // 2.3) Arquivos obrigatórios (exceto docbeneficio)
      ['docrg','doccpf','doctitu','docresi','docnis','docctps']
        .forEach(id => {
          const inp = document.getElementById(id);
          if (!inp || inp.files.length === 0) {
            if (inp) inp.style.borderColor = 'red';
            msgs.push(`Anexe o documento "${inp.previousElementSibling.innerText}".`);
          } else {
            inp.style.borderColor = '';
          }
        });
  
      // 3) Se houver erros, bloqueia e mostra alert
      if (msgs.length) {
        e.preventDefault();
        alert(msgs.join('\n'));
        return;
      }
  
      // 4) Se tudo OK, desabilita o botão
      const btn = form.querySelector("button[type='submit']");
      if (btn) {
        btn.disabled = true;
        btn.textContent = "Enviando...";
      }
      // form será submetido normalmente
    });
  });
  
  // ————————————————————————————————
  // Funções auxiliares de validação
  // ————————————————————————————————
  
  function validarRG(rg) {
    return rg.replace(/\D/g, '').length >= 7;
  }
  
  function validarNomeCompleto(nome) {
    const partes = nome.trim().split(/\s+/);
    return partes.length >= 2 && nome.length >= 5 && !/\d/.test(nome);
  }
  
  function validarEmissor(org) {
    return /^[A-Z]{2,10}$/.test(org.trim());
  }
  
  function validarRenda() {
    const field = document.getElementById('renda');
    if (!field) return true;
    const val = parseInt(field.value.replace(/\D/g, '') || '0') / 100;
    if (val === 0 || val > 20000) {
      field.style.borderColor = 'red';
      return false;
    }
    field.style.borderColor = '';
    return true;
  }
  