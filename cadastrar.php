<?php
session_start();

// ───── Base URL ─────
$path     = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$base_url = ($path === '' || $path === '.') ? '/' : $path.'/';

// ───── Logs ─────
$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) mkdir($logDir, 0755, true);
ini_set('log_errors', 1);
ini_set('display_errors', 0);
ini_set('error_log', "$logDir/".date('Y-m-d').".log");

// ─── 1) Só POST ───
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro_cadastro'] = 'Método de requisição inválido.';
    header("Location: {$base_url}cadastro.php");
    exit;
}

// ─── 2) CSRF ───
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['erro_cadastro'] = 'Falha na verificação de segurança.';
    header("Location: {$base_url}cadastro.php");
    exit;
}

// ─── 3) Conecta ───
require __DIR__ . '/conectar.php';

try {
    // ─── 4) Garante uploadDir ───
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    // ─── 5) Campos de texto (sem arquivos) ───
    $cpf   = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $rg    = trim($_POST['rg'] ?? '');
    $nis   = preg_replace('/\D/', '', $_POST['nis'] ?? '');
    $dadosText = [
        'nome'        => trim($_POST['nome']        ?? ''),
        'cpf'         => $cpf,
        'rg'          => $rg,
        'emissor'     => trim($_POST['emissor']     ?? ''),
        'nascimento'  => $_POST['nascimento']       ?? '',
        'endereco'    => trim($_POST['endereco']    ?? ''),
        'numero'      => trim($_POST['numero']      ?? ''),
        'bairro'      => trim($_POST['bairro']      ?? ''),
        'cidade'      => trim($_POST['cidade']      ?? ''),
        'uf'          => trim($_POST['uf']          ?? ''),
        'cep'         => preg_replace('/\D/', '', $_POST['cep'] ?? ''),
        'sexo'        => $_POST['sexo']             ?? '',
        'estadoci'    => $_POST['estadoci']         ?? '',
        'escolaridade'=> $_POST['escolaridade']     ?? '',
        'renda'       => preg_replace('/[^0-9.,]/','', $_POST['renda'] ?? ''),
        'fone'        => preg_replace('/\D/', '', $_POST['fone'] ?? ''),
        'email'       => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
        'auxmor'      => $_POST['auxmor']           ?? '',
        'idoso'       => $_POST['idoso']            ?? '',
        'mae'         => trim($_POST['mae']         ?? ''),
        'situacaoend' => $_POST['situacaoend']      ?? '',
        'tempresi'    => $_POST['tempresi']         ?? '',
        'datacad'     => date('Y-m-d'),
        'beneficio'   => '',
        'especial'    => ''
    ];

    // ─── 6) Transaction ───
    $pdo->beginTransaction();

    // ─── 7) Primeiro INSERT (texto) ───
    $cols = implode(', ', array_keys($dadosText));
    $ph   = ':'.implode(', :', array_keys($dadosText));
    $stmt = $pdo->prepare("INSERT INTO assistido ($cols) VALUES ($ph)");
    $stmt->execute($dadosText);
    $id = $pdo->lastInsertId();

        // ─── 8) Processa uploads, nomeando com o id ───
        $required    = ['docrg','doccpf','doctitu','docresi','docnis','docctps'];
        $updatePaths = [];
        $maxSize     = 2 * 1024 * 1024; // 2 MB
    
        foreach ($required as $f) {
            // 8.1) campo enviado?
            if (!isset($_FILES[$f]) || $_FILES[$f]['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Falta o arquivo obrigatório: $f");
            }
    
            // 8.2) tamanho máximo
            if ($_FILES[$f]['size'] > $maxSize) {
                throw new Exception("O arquivo “{$f}” excede o tamanho máximo de 2 MB.");
            }
    
            // 8.3) tipo e extensão
            $ext  = strtolower(pathinfo($_FILES[$f]['name'], PATHINFO_EXTENSION));
            $mime = (new finfo(FILEINFO_MIME_TYPE))
                        ->file($_FILES[$f]['tmp_name']);
            if (!in_array($ext, ['pdf','jpg','jpeg','png']) ||
                !in_array($mime, ['application/pdf','image/jpeg','image/png'])
            ) {
                throw new Exception("Tipo não permitido para “{$f}”: {$ext}");
            }
    
            // 8.4) monta nome e move
            $filename = "{$id}_{$f}.{$ext}";
            $dest     = $uploadDir . $filename;
            if (!move_uploaded_file($_FILES[$f]['tmp_name'], $dest)) {
                throw new Exception("Falha ao mover arquivo {$f} para o servidor.");
            }
    
            $updatePaths[$f] = 'uploads/'.$filename;
        }
    // Agora trate o docbeneficio como opcional:
    if (isset($_FILES['docbeneficio']) && $_FILES['docbeneficio']['error'] === UPLOAD_ERR_OK) {
        $ext  = strtolower(pathinfo($_FILES['docbeneficio']['name'], PATHINFO_EXTENSION));
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['docbeneficio']['tmp_name']);
        if (in_array($ext, ['pdf','jpg','jpeg','png']) && in_array($mime, ['application/pdf','image/jpeg','image/png'])) {
            $filename = "{$id}_docbeneficio.{$ext}";
            $dest     = $uploadDir . $filename;
            if (!move_uploaded_file($_FILES['docbeneficio']['tmp_name'], $dest)) {
                throw new Exception("Falha ao mover arquivo docbeneficio");
            }
            $updatePaths['docbeneficio'] = 'uploads/'.$filename;
        }
    }
    // ─── 9) Atualiza apenas os campos de arquivo ───
    if ($updatePaths) {
        $sets   = [];
        $params = [];
        foreach ($updatePaths as $col => $path) {
            $sets[]       = "`$col` = :$col";
            $params[$col] = $path;
        }
        $params['id']    = $id;
        $sql2            = 'UPDATE assistido SET '.implode(', ', $sets).' WHERE id = :id';
        $stmt2           = $pdo->prepare($sql2);
        $stmt2->execute($params);
    }

        // ─── 10) Commit e redireciona ───
        $pdo->commit();
        $_SESSION['cadastro_id'] = $id;
        header("Location: {$base_url}comprovante.php");
        exit;
    
    } catch (\PDOException $e) {
        // ─── rollback + cleanup uploads já movidos ───
        if ($pdo->inTransaction()) $pdo->rollBack();
        if (!empty($updatePaths)) {
            foreach ($updatePaths as $path) {
                @unlink(__DIR__ . '/' . $path);
            }
        }
    
        error_log("Erro PDO no processamento: " . $e->getMessage());
    
        // Se for violação de UNIQUE (1062), mostra mensagem amigável
        $mysqlErrorCode = $e->errorInfo[1] ?? null;
        if ($mysqlErrorCode === 1062) {
            $_SESSION['erro_cadastro'] = 'Já existe um cadastro com este CPF, RG ou NIS.';
        } else {
            $_SESSION['erro_cadastro'] = 'Ocorreu um erro ao processar seu cadastro. Por favor, tente novamente.';
        }
    
        header("Location: {$base_url}cadastro.php");
        exit;
    
    } catch (\Exception $e) {
        // ─── rollback + cleanup uploads já movidos ───
        if ($pdo->inTransaction()) $pdo->rollBack();
        if (!empty($updatePaths)) {
            foreach ($updatePaths as $path) {
                @unlink(__DIR__ . '/' . $path);
            }
        }
    
        error_log("Erro inesperado no processamento: " . $e->getMessage());
        $_SESSION['erro_cadastro'] = 'Ocorreu um erro inesperado. Por favor, tente novamente.';
        header("Location: {$base_url}cadastro.php");
        exit;
    }
    