<?php
require_once __DIR__ . '/../model/Pedido.php';

class PedidoController
{
    public static function index()
    {
        include __DIR__ . '/../config/conexao.php';
        $produtos = Pedido::listarProdutos($conn);
        require __DIR__ . '/../view/pedidos/index.php';
    }
}
