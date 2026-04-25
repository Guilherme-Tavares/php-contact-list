<?php
require_once 'config/db.php';

// Captura o termo de busca enviado pelo formulário
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Busca contatos com filtro opcional por nome
if ($search !== '') {
    $stmt = mysqli_prepare($conn, "SELECT id, name, phone, category FROM contacts WHERE name LIKE ? ORDER BY name ASC");
    $like = '%' . $search . '%';
    mysqli_stmt_bind_param($stmt, 's', $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT id, name, phone, category FROM contacts ORDER BY name ASC");
}

$contacts = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Contatos</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Agenda</h1>
        <a href="create.php" class="btn btn-primary btn-sm">+ Novo contato</a>
    </div>
</header>

<main class="container">

    <!-- Formulário de busca por nome -->
    <form method="GET" action="index.php" class="search-bar">
        <input
            type="text"
            name="search"
            placeholder="Buscar contato..."
            value="<?= htmlspecialchars($search) ?>"
            autocomplete="off"
        >
        <button type="submit" class="btn btn-secondary btn-sm">Buscar</button>
        <?php if ($search !== ''): ?>
            <a href="index.php" class="btn btn-secondary btn-sm">Limpar</a>
        <?php endif; ?>
    </form>

    <?php if (empty($contacts)): ?>
        <!-- Estado vazio -->
        <div class="empty-state">
            <p><?= $search !== '' ? 'Nenhum contato encontrado para "' . htmlspecialchars($search) . '".' : 'Nenhum contato cadastrado ainda.' ?></p>
            <a href="create.php" class="btn btn-primary">Adicionar primeiro contato</a>
        </div>
    <?php else: ?>
        <!-- Lista de contatos -->
        <ul class="contact-list">
            <?php foreach ($contacts as $contact): ?>
                <?php $initial = mb_strtoupper(mb_substr($contact['name'], 0, 1, 'UTF-8'), 'UTF-8'); ?>
                <li class="contact-item">
                    <a href="view.php?id=<?= $contact['id'] ?>" class="contact-link">
                        <div class="avatar" data-letter="<?= htmlspecialchars($initial) ?>"><?= htmlspecialchars($initial) ?></div>
                        <div class="contact-info">
                            <span class="contact-name"><?= htmlspecialchars($contact['name']) ?></span>
                            <span class="contact-phone"><?= htmlspecialchars($contact['phone']) ?></span>
                        </div>
                    </a>
                    <div class="contact-actions">
                        <a href="edit.php?id=<?= $contact['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                        <a href="delete.php?id=<?= $contact['id'] ?>" class="btn btn-danger btn-sm" data-confirm="Excluir <?= htmlspecialchars($contact['name']) ?>?">Excluir</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</main>

<footer>
    <div class="container">
        Agenda de Contatos &mdash; Guilherme Maricato Tavares
    </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
