// mascaras.js otimizado

document.addEventListener('DOMContentLoaded', function () {
    aplicarMascaras();
});

function aplicarMascaras() {
    mascararCPF('cpf');
    mascararCEP('cep');
    mascararTelefone('fone');
    mascararNIS('nis');
    mascararRG('rg');
    configurarMascaraRenda('renda');
    padronizarEmissor('emissor');
}

function mascararCPF(id) {
    const field = document.getElementById(id);
    if (field) {
        field.addEventListener('input', function (e) {
            let v = e.target.value.replace(/\D/g, '').substring(0, 11);
            e.target.value = v
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        });
    }
}

function mascararCEP(id) {
    const field = document.getElementById(id);
    if (field) {
        field.addEventListener('input', function (e) {
            let v = e.target.value.replace(/\D/g, '').substring(0, 8);
            e.target.value = v.replace(/(\d{5})(\d)/, '$1-$2');
        });
    }
}

function mascararTelefone(id) {
    const field = document.getElementById(id);
    if (field) {
        field.addEventListener('input', function (e) {
            let v = e.target.value.replace(/\D/g, '').substring(0, 11);
            e.target.value = v
                .replace(/(\d{2})(\d)/, '($1) $2')
                .replace(/(\d{5})(\d{4})$/, '$1-$2');
        });
    }
}

function mascararNIS(id) {
    const field = document.getElementById(id);
    if (field) {
        field.addEventListener('input', function (e) {
            let v = e.target.value.replace(/\D/g, '').substring(0, 11);
            e.target.value = v
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})\.(\d{5})(\d{2})?/, '$1.$2.$3');
        });
    }
}

function mascararRG(id) {
    const field = document.getElementById(id);
    if (field) {
        field.addEventListener('input', function (e) {
            let v = e.target.value.replace(/\D/g, '').substring(0, 9);
            v = v
                .replace(/(\d{2})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d{1})$/, '$1-$2');
            e.target.value = v;
        });
    }
}

function configurarMascaraRenda(id) {
    const field = document.getElementById(id);
    if (!field) return;

    field.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        value = Math.min(parseInt(value || '0'), 2000000).toString();
        e.target.value = formatAsCurrency(value);
    });

    field.addEventListener('keydown', function (e) {
        if ([8, 9, 35, 36, 37, 38, 39, 40, 46].includes(e.keyCode)) return;
        if (!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105))) {
            e.preventDefault();
        }
    });
}

function formatAsCurrency(value) {
    return (parseInt(value || '0') / 100).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2
    });
}

function padronizarEmissor(id) {
    const field = document.getElementById(id);
    if (!field) return;

    field.addEventListener('blur', function (e) {
        let org = e.target.value
            .toUpperCase()
            .trim()
            .replace(/\s+/g, ' ')
            .replace(/[^A-Z\s]/g, '');

        const padroes = {
            'SSP DS': 'SSP',
            'DETRAN': 'DETRAN',
            'PC': 'PC',
            'PM': 'PM',
            'CNH': 'CNH'
        };

        for (const [key, val] of Object.entries(padroes)) {
            if (org.includes(key)) {
                org = val;
                break;
            }
        }

        e.target.value = org;

        if (org.length > 0 && (org.length < 2 || org.length > 10)) {
            showEmissorFeedback('Sigla deve ter entre 2 e 10 caracteres');
        } else {
            hideEmissorFeedback();
        }
    });
}

function showEmissorFeedback(message) {
    let feedback = document.getElementById('emissor-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.id = 'emissor-feedback';
        feedback.className = 'field-feedback';
        document.getElementById('emissor').parentNode.appendChild(feedback);
    }
    feedback.textContent = message;
    feedback.style.display = 'block';
}

function hideEmissorFeedback() {
    const feedback = document.getElementById('emissor-feedback');
    if (feedback) feedback.style.display = 'none';
}
