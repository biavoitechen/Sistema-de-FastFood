<?php
require_once './controllers/PedidoController.php';

$acao = $_GET['action'] ?? null;

if ($acao === 'atualizar') {
    PedidoController::atualizarStatusPedido();
} else {
    PedidoController::exibirTelaAtendente();
}
?>