<?php if (!isset($usuarios)) $usuarios = []; ?> <!-- Verifica se a variável $usuarios está definida -->
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= $usuario['tipo'] ?></td>
                <td><?= ($usuario['ativo'] ?? 1) ? 'Ativo' : 'Bloqueado' ?></td>
                <td>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="user_action" value="toggle_status">
                        <input type="hidden" name="user_id" value="<?= $usuario['id'] ?>">
                        <input type="hidden" name="new_status" value="<?= ($usuario['ativo'] ?? 1) ? 0 : 1 ?>">
                        <button type="submit" class="btn btn-sm btn-<?= ($usuario['ativo'] ?? 1) ? 'warning' : 'success' ?>">
                            <?= ($usuario['ativo'] ?? 1) ? 'Bloquear' : 'Ativar' ?>
                        </button>
                    </form>

                    <form method="post" class="d-inline" onsubmit="return confirm('Tem certeza?')">
                        <input type="hidden" name="user_action" value="delete">
                        <input type="hidden" name="user_id" value="<?= $usuario['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>