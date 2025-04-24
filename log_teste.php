<?php
$logDir = __DIR__ . '/logs/';
if (!file_exists($logDir)) {
    mkdir($logDir, 0755, true);
}
ini_set('log_errors', 1);
ini_set('display_errors', 0);
ini_set('error_log', $logDir . date('Y-m-d') . '.log');

error_log("Teste de log gerado com sucesso!");
echo "Verifique a pasta logs/";
