<h2>Pedidos Recebidos</h2>

<?php foreach ($pedidos as $pedido): ?>
    <div style="border:1px solid #ccc; margin:10px; padding:10px;">
        <p><strong>Pedido #<?= $pedido['id'] ?></strong></p>
        <p>Data/Hora: <?= $pedido['data_hora'] ?></p>
        <p>Status: <?= $pedido['status'] ?></p>

        <p><u>Itens:</u></p>
        <ul>
            <?php foreach ($pedido['itens'] as $item): 
                $subtotal = $item['quantidade'] * $item['preco_unitario'];
            ?>
                <li><?= $item['quantidade'] ?>x <?= $item['nome'] ?> - R$ <?= number_format($subtotal, 2, ',', '.') ?></li>
            <?php endforeach; ?>
        </ul>

        <p><strong>Total: R$ <?= number_format($pedido['total'], 2, ',', '.') ?></strong></p>

        <form method="post" action="atendente.php?action=atualizar">
            <input type="hidden" name="id_pedido" value="<?= $pedido['id'] ?>">

            <?php if ($pedido['status'] === 'pendente'): ?>
                <button type="submit" name="novo_status" value="preparando">Iniciar Preparo</button>
            <?php elseif ($pedido['status'] === 'preparando'): ?>
                <button type="submit" name="novo_status" value="pronto">Finalizar Preparo</button>
            <?php elseif ($pedido['status'] === 'pronto'): ?>
                <button type="submit" name="novo_status" value="entregue">Entregar</button>
            <?php endif; ?>
        </form>
    </div>
<?php endforeach; ?>
