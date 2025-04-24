<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Detecta raiz do projeto dinamicamente
$path      = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$base_url  = ($path === '' || $path === '.') ? '/' : $path . '/';
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// ─────── CÁLCULO DO BASE_URL ───────
// Substitui qualquer menção a “php/” e garante que a raiz seja detectada
$script = $_SERVER['SCRIPT_NAME'];                     // ex: "/ita_mcmv/cadastro.php"
$base_url = rtrim(dirname($script), '/') . '/';       // ex: "/ita_mcmv/"
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - MCMV Itabaiana</title>
    <link rel="stylesheet" href="css/estilo.css">
    <script src="js/mascaras.js" defer></script>
    <script src="js/formulario.js" defer></script>
</head>
<body>


    <header>
        <div class="container">
            <img src="assets/ita_43fcfaacc854e846ae5d7.png" alt="Prefeitura de Itabaiana">
            <nav>
                <a href="index.php">Voltar</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php if (isset($_SESSION['erro_cadastro'])): ?>
        <div class="alert-danger">
            <?= htmlspecialchars($_SESSION['erro_cadastro']) ?>
        </div>
        <?php unset($_SESSION['erro_cadastro']); ?>
        <?php endif; ?>

        <form id="formCadastro" method="POST" action="<?= $base_url ?>cadastrar.php" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <h2>Formulário de Cadastro</h2>
            
            <!-- Dados Pessoais -->
            <fieldset>
                <legend>Dados Pessoais</legend>
                <div class="form-group">
                    <label for="nome">Nome Completo*</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cpf">CPF*</label>
                        <input type="text" id="cpf" name="cpf" required>
                    </div>
                    <div class="form-group">
                        <label for="nascimento">Data Nascimento*</label>
                        <input type="date" id="nascimento" name="nascimento" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="rg">RG*</label>
                        <input type="text" id="rg" name="rg" required>
                    </div>
                    <div class="form-group">
                        <label for="emissor">Órgão Emissor*</label>
                        <input type="text" id="emissor" name="emissor" required>
                    </div>
                    <div class="form-group">
                        <label for="nis">NIS*</label>
                        <input type="text" id="nis" name="nis" required>
                    </div>
                </div>
                <div class="flex flex-wrap gap-4">

                    <div class="flex flex-col" style="flex: 1 1 250px;">
                    <label for="escolaridade" class="text-left text-white text-sm sm:text-base font-bold">Escolaridade:</label>
                    <select id="escolaridade" name="escolaridade" required class="border rounded p-1 text-xs sm:text-sm">
                        <option value="analfabeto">Analfabeto</option>
                        <option value="5º Ano Incompleto">Até 5º Ano Incompleto</option>
                        <option value="5º Ano Completo">5º Ano Completo</option>
                        <option value="6º ao 9º Ano do Fundamental">6º ao 9º Ano do Fundamental</option>
                        <option value="Fundamental Completo">Fundamental Completo</option>
                        <option value="Médio Incompleto">Médio Incompleto</option>
                        <option value="Médio Completo">Médio Completo</option>
                        <option value="Superior Incompleto">Superior Incompleto</option>
                        <option value="Superior Completo">Superior Completo</option>
                        <option value="Mestrado">Mestrado</option>
                        <option value="Doutorado">Doutorado</option>
                    </select>
                    </div>

                    <div class="flex flex-col" style="flex: 1 1 150px;">
                    <label for="renda" class="text-left text-white text-sm sm:text-base font-bold">Renda:</label>
                    <input type="text" id="renda" name="renda" placeholder="R$ 0,00" required class="border rounded p-1 text-xs sm:text-sm" />
                    </div>

                    <div class="flex flex-col" style="flex: 1 1 200px;">
                    <label for="fone" class="text-left text-white text-sm sm:text-base font-bold">Telefone:</label>
                    <input type="text" id="fone" name="fone" required class="border rounded p-1 text-xs sm:text-sm" data-mask="(99) 99999-9999" />
                    </div>

                    <div class="flex flex-col" style="flex: 1 1 250px;">
                    <label for="email" class="text-left text-white text-sm sm:text-base font-bold">Email:</label>
                    <input type="email" id="email" name="email" required class="border rounded p-1 text-xs sm:text-sm" />
                    </div>

                    <div class="flex flex-col" style="flex: 1 1 150px;">
                    <label for="sexo" class="text-left text-white text-sm sm:text-base font-bold">Gênero:</label>
                    <select id="sexo" name="sexo" required class="border rounded p-1 text-xs sm:text-sm">
                        <option value="masculino">Masculino</option>
                        <option value="feminino">Feminino</option>
                        <option value="outro">Outro</option>
                    </select>
                    </div>

                    <div class="flex flex-col" style="flex: 1 1 150px;">
                    <label for="estadoci" class="text-left text-white text-sm sm:text-base font-bold">Estado Civil:</label>
                    <select id="estadoci" name="estadoci" required class="border rounded p-1 text-xs sm:text-sm">
                        <option value="solteiro">Solteiro</option>
                        <option value="casado">Casado</option>
                        <option value="viuvo">Viúvo</option>
                        <option value="divorciado">Divorciado</option>
                    </select>
                    </div>

                    <div class="flex flex-col" style="flex: 1 1 150px;">
                    <label for="auxmor" class="text-left text-white text-sm sm:text-base font-bold">Auxílio Moradia?</label>
                    <select id="auxmor" name="auxmor" required class="border rounded p-1 text-xs sm:text-sm">
                        <option value="sim">Sim</option>
                        <option value="nao">Não</option>
                    </select>
                    </div>

                    <div class="flex flex-col" style="flex: 1 1 150px;">
                    <label for="idoso" class="text-left text-white text-sm sm:text-base font-bold">É Idoso?</label>
                    <select id="idoso" name="idoso" required class="border rounded p-1 text-xs sm:text-sm">
                        <option value="sim">Sim</option>
                        <option value="nao">Não</option>
                    </select>
                    </div>

                    <div class="flex flex-col" style="flex: 1 1 150px;">
                    <label for="mae" class="text-left text-white text-sm sm:text-base font-bold">É Mãe Solteira?</label>
                    <select id="mae" name="mae" required class="border rounded p-1 text-xs sm:text-sm">
                        <option value="sim">Sim</option>
                        <option value="nao">Não</option>
                    </select>
                    </div>

                </div>
            </fieldset>
            
            <!-- Endereço -->
                <fieldset class="max-w border border-gray-300 rounded p-4">
        <legend class="text-white text-sm sm:text-base font-bold px-2">Situação Residencial e Endereço</legend>

        <div class="flex flex-col gap-2 mb-4">
        <label for="situacaoend" class="text-left text-white text-sm sm:text-base font-bold">Situação do Endereço:</label>
        <select id="situacaoend" name="situacaoend" required class="max-w border rounded p-1 text-xs sm:text-sm">
            <option value="proprio">Próprio</option>
            <option value="alugado">Alugado</option>
            <option value="cedido">Cedido</option>
            <option value="outro">Outro</option>
        </select>

        <label for="tempresi" class="text-left text-white text-sm sm:text-base font-bold">Tempo no município:</label>
        <select id="tempresi" name="tempresi" required class="max-w border rounded p-1 text-xs sm:text-sm">
            <option value="-1">Menos de 1 ano</option>
            <option value="1-3">Entre 1 e 3 anos</option>
            <option value="4-5">Entre 4 e 5 anos</option>
            <option value="+5">Mais de 5 anos</option>
        </select>
        </div>

        <div class="flex flex-col gap-2 mb-2">
        <label for="cep" class="text-left text-white text-sm sm:text-base font-bold">CEP*</label>
        <input type="text" id="cep" name="cep" required class="max-w border rounded p-1 text-xs sm:text-sm" />
        </div>

        <div class="flex gap-2 mb-2">
        <div class="flex-3 flex flex-col">
            <label for="endereco" class="text-left text-white text-sm sm:text-base font-bold">Endereço*</label>
            <input type="text" id="endereco" name="endereco" required class="max-w border rounded p-1 text-xs sm:text-sm" />
        </div>
        <div class="flex-1 flex flex-col">
            <label for="numero" class="text-left text-white text-sm sm:text-base font-bold">Número*</label>
            <input type="text" id="numero" name="numero" required class="max-w border rounded p-1 text-xs sm:text-sm" />
        </div>
        </div>

        <div class="flex gap-2 mb-2">
        <div class="flex-1 flex flex-col">
            <label for="bairro" class="text-left text-white text-sm sm:text-base font-bold">Bairro*</label>
            <input type="text" id="bairro" name="bairro" required class="max-w border rounded p-1 text-xs sm:text-sm" />
        </div>
        <div class="flex-1 flex flex-col">
            <label for="cidade" class="text-left text-white text-sm sm:text-base font-bold">Cidade*</label>
            <input type="text" id="cidade" name="cidade" value="Itabaiana" required class="max-w border rounded p-1 text-xs sm:text-sm" />
        </div>
        <div class="flex-[0.5] flex flex-col">
            <label for="uf" class="text-left text-white text-sm sm:text-base font-bold">UF*</label>
            <input type="text" id="uf" name="uf" value="SE" maxlength="2" required class="max-w border rounded p-1 text-xs sm:text-sm" />
        </div>
        </div>
            </fieldset>
            
           <!-- Documentos -->
        <fieldset>
            <legend>Documentos</legend>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Coluna 1 -->
                <div class="flex flex-col gap-2">
                    <label for="docrg" class="text-center text-white text-lg font-bold">Anexar RG:</label>
                    <input type="file" id="docrg" name="docrg" required accept=".pdf,.jpg,.jpeg,.png" class="w-full border rounded p-1" required onchange="validateDocument(this)">

                    <label for="doccpf" class="text-center text-white text-lg font-bold">Anexar CPF:</label>
                    <input type="file" id="doccpf" name="doccpf" required accept=".pdf,.jpg,.jpeg,.png" class="w-full border rounded p-1" required onchange="validateDocument(this)">

                    <label for="doctitu" class="text-center text-white text-lg font-bold">Anexar Título de Eleitor:</label>
                    <input type="file" id="doctitu" name="doctitu" required accept=".pdf,.jpg,.jpeg,.png" class="w-full border rounded p-1" required onchange="validateDocument(this)">

                    <label for="docresi" class="text-center text-white text-lg font-bold">Comprovante de Residência:</label>
                    <input type="file" id="docresi" name="docresi" required accept=".pdf,.jpg,.jpeg,.png" class="w-full border rounded p-1" required onchange="validateDocument(this)">
                </div>

                <!-- Coluna 2 -->
                <div class="flex flex-col gap-2">
                    <label for="docnis" class="text-center text-white text-lg font-bold">Anexar NIS:</label>
                    <input type="file" id="docnis" name="docnis" required accept=".pdf,.jpg,.jpeg,.png" class="w-full border rounded p-1" required onchange="validateDocument(this)">

                    <label for="docbeneficio" class="text-center text-white text-lg font-bold">Anexar Documento de Benefício INSS:</label>
                    <input type="file" id="docbeneficio" name="docbeneficio" accept=".pdf,.jpg,.jpeg,.png" class="w-full border rounded p-1" onchange="validateDocument(this)">

                    <label for="docctps" class="text-center text-white text-lg font-bold">Carteira de Trabalho:</label>
                    <input type="file" id="docctps" name="docctps" required accept=".pdf,.jpg,.jpeg,.png" class="w-full border rounded p-1" required onchange="validateDocument(this)">
                </div>
            </div>
        </fieldset>

            
            <button type="submit" class="btn">Enviar Cadastro</button>
        </form>
    </main>
    <script src="js/mascaras.js"></script>
    <script src="js/formulario.js"></script>
</body>
</html>