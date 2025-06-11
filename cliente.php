<?php
session_start();
require_once './model/Conexao.php';
require_once './model/Pedido.php';

$conn = Conexao::conectar();
$produtos = Pedido::listarProdutos($conn);

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['produto_id'])) {
        $_SESSION['carrinho'][] = $_POST['produto_id'];
        header("Location: cliente.php");
        exit;
    }

    if (isset($_POST['finalizar']) && count($_SESSION['carrinho']) > 0) {
        $idPedido = Pedido::salvarPedido($conn, $_SESSION['carrinho']);
        unset($_SESSION['carrinho']);
        echo "<p><strong>Pedido #$idPedido realizado com sucesso!</strong></p>";
    }
}

?>

<h2>Cardápio</h2>

<form method="post">
    <ul>
        <?php foreach ($produtos as $produto): ?>
            <li>
                <?= htmlspecialchars($produto['nome']) ?> - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                <button type="submit" name="produto_id" value="<?= $produto['id'] ?>">Adicionar ao carrinho</button>
            </li>
        <?php endforeach; ?>
    </ul>
</form>

<h3>Carrinho:</h3>
<ul>
    <?php
    foreach ($_SESSION['carrinho'] as $idProduto) {
        foreach ($produtos as $produto) {
            if ($produto['id'] == $idProduto) {
                echo "<li>{$produto['nome']} - R$ " . number_format($produto['preco'], 2, ',', '.') . "</li>";
                break;
            }
        }
    }
    ?>
</ul>

<form method="post" action="cliente.php">
    <input type="hidden" name="finalizar" value="1">
    <button type="submit">Finalizar Pedido</button>
</form>
