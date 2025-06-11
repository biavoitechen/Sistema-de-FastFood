<?php

class Pedido
{
    public static function listarProdutos($conn)
    {
        $sql = "SELECT * FROM produtos ORDER BY nome ASC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function salvarPedido($conn, $itens, $idUsuario = 1)
    {
        $stmt = $conn->prepare("INSERT INTO pedidos (id_usuario, forma_pagamento, status) VALUES (?, 'dinheiro', 'pendente')");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $idPedido = $conn->insert_id;

        foreach ($itens as $idProduto) {
            $produtoStmt = $conn->prepare("SELECT preco FROM produtos WHERE id = ?");
            $produtoStmt->bind_param("i", $idProduto);
            $produtoStmt->execute();
            $produtoResult = $produtoStmt->get_result()->fetch_assoc();

            $preco = $produtoResult['preco'];
            $quantidade = 1;

            $itemStmt = $conn->prepare("INSERT INTO pedido_itens (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
            $itemStmt->bind_param("iiid", $idPedido, $idProduto, $quantidade, $preco);
            $itemStmt->execute();
        }

        return $idPedido;
    }

    public static function listarPedidosPendentes($conn)
    {
        $sql = "SELECT * FROM pedidos WHERE status != 'entregue' ORDER BY data_hora ASC";
        return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public static function listarItensDoPedido($conn, $idPedido)
    {
        $sql = "SELECT p.nome, pi.quantidade, pi.preco_unitario
                FROM pedido_itens pi
                JOIN produtos p ON p.id = pi.id_produto
                WHERE pi.id_pedido = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function atualizarStatus($conn, $idPedido, $novoStatus)
    {
        $stmt = $conn->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $novoStatus, $idPedido);
        return $stmt->execute();
    }
}
