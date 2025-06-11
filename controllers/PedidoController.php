<?php
require_once './model/Conexao.php';
require_once './model/Pedido.php';

class PedidoController
{
    public static function exibirTelaAtendente()
    {
        $conn = Conexao::conectar();
        $pedidos = Pedido::listarPedidosPendentes($conn);

        foreach ($pedidos as &$pedido) {
            $itens = Pedido::listarItensDoPedido($conn, $pedido['id']);
            $pedido['itens'] = $itens;

            $total = 0;
            foreach ($itens as $item) {
                $total += $item['quantidade'] * $item['preco_unitario'];
            }
            $pedido['total'] = $total;
        }

        require './view/telaatendente.php';

    }

    public static function atualizarStatusPedido()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido'], $_POST['novo_status'])) {
            $conn = Conexao::conectar();
            Pedido::atualizarStatus($conn, $_POST['id_pedido'], $_POST['novo_status']);
        }

        header("Location: atendente.php");
        exit;
    }
}
?>      