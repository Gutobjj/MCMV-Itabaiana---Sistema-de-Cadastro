<?php
session_start();

// ─── Base URL ───
$path     = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$base_url = ($path === '' || $path === '.') ? '/' : $path . '/';

// ─── Se não há cadastro_id, volta pro formulário
if (empty($_SESSION['cadastro_id'])) {
    header('Location: ' . $base_url . 'cadastro.php');
    exit;
}

require_once __DIR__ . '/conectar.php';

// ─── Busca o cadastro
$stmt = $pdo->prepare("SELECT * FROM assistido WHERE id = ?");
$stmt->execute([$_SESSION['cadastro_id']]);
$cad = $stmt->fetch();

if (!$cad) {
    $_SESSION['erro_cadastro'] = 'Cadastro não encontrado.';
    header('Location: ' . $base_url . 'cadastro.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="description"
        content="Comprovante de inscrição no Programa Minha Casa Minha Vida - Prefeitura de Itabaiana">
  <title>Comprovante de Cadastro</title>

  <!-- Usa a base_url para montar o caminho correto -->
  <link rel="stylesheet" href="<?= $base_url ?>css/estilo.css">
  <link rel="stylesheet" href="<?= $base_url ?>css/comprovante.css">
</head>
<body>
    <header>
        <div class="container">
            <img src="assets/ita_43fcfaacc854e846ae5d7.png" alt="Prefeitura de Itabaiana">
        </div>
    </header>

    <main class="container">
        <div class="comprovante">
            <h2>COMPROVANTE DE CADASTRO</h2>
            <p><strong>Número do Cadastro:</strong> <?= $cad['id'] ?></p>
            <p><strong>Nome:</strong> <?= htmlspecialchars($cad['nome']) ?></p>
            <p><strong>CPF:</strong> <?= htmlspecialchars($cad['cpf']) ?></p>
            <p><strong>RG:</strong> <?= htmlspecialchars($cad['rg']) ?></p>
            <p><strong>Data de Nascimento:</strong> <?= htmlspecialchars($cad['nascimento']) ?></p>

            <h3>Endereço</h3>
            <p><?= htmlspecialchars($cad['endereco']) ?>, <?= htmlspecialchars($cad['numero']) ?></p>
            <p>Bairro: <?= htmlspecialchars($cad['bairro']) ?></p>
            <p>Cidade: <?= htmlspecialchars($cad['cidade']) ?> - <?= htmlspecialchars($cad['uf']) ?></p>
            <p>CEP: <?= htmlspecialchars($cad['cep']) ?></p>

            <h3>Contato</h3>
            <p>Telefone: <?= htmlspecialchars($cad['fone']) ?></p>
            <p>Email: <?= htmlspecialchars($cad['email']) ?></p>

            <div class="info">
                <p>Guarde este número para futuras consultas.</p>
                <p>O cadastro será analisado pela equipe técnica.</p>
                <p>Você pode imprimir ou salvar este comprovante em PDF.</p>
            </div>

            <div class="actions">
                <button onclick="window.print()">Imprimir Comprovante</button>
                <a href="<?= $base_url ?>index.php" class="btn">Voltar ao Início</a>
            </div>
        </div>
    </main>
</body>
</html>