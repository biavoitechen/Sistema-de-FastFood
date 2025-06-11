<?php

class Pedido
{
    public static function listarProdutos($conn)
    {
        $sql = "SELECT * FROM produtos ORDER BY nome ASC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

public static function salvarPedido($conn, $itens, $idUsuario = 1) {
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
