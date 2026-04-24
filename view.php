<?php
require_once 'config/db.php';

// Valida o parâmetro id recebido na URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Busca o contato pelo id
$stmt = mysqli_prepare($conn, "SELECT * FROM contacts WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result  = mysqli_stmt_get_result($stmt);
$contact = mysqli_fetch_assoc($result);

// Redireciona se o contato não existir
if (!$contact) {
    header('Location: index.php');
    exit;
}

$categories = ['personal' => 'Pessoal', 'work' => 'Trabalho', 'family' => 'Família', 'other' => 'Outro'];
$initial    = mb_strtoupper(mb_substr($contact['name'], 0, 1, 'UTF-8'), 'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($contact['name']) ?> — Agenda</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Agenda</h1>
    </div>
</header>

<main class="container">

    <div class="page-nav">
        <h2>Detalhes do contato</h2>
        <a href="index.php" class="btn btn-secondary btn-sm">&larr; Voltar</a>
    </div>

    <div class="card">

        <!-- Cabeçalho com avatar e nome -->
        <div class="card-header">
            <div class="avatar" data-letter="<?= htmlspecialchars($initial) ?>" style="width:64px;height:64px;font-size:1.7rem;">
                <?= htmlspecialchars($initial) ?>
            </div>
            <div class="card-header-info">
                <h2><?= htmlspecialchars($contact['name']) ?></h2>
                <p>
                    <span class="badge badge-<?= $contact['category'] ?>">
                        <?= $categories[$contact['category']] ?? $contact['category'] ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Campos do contato -->
        <div class="detail-row">
            <span class="detail-label">Telefone</span>
            <span class="detail-value"><?= htmlspecialchars($contact['phone']) ?></span>
        </div>

        <?php if ($contact['email'] !== ''): ?>
        <div class="detail-row">
            <span class="detail-label">E-mail</span>
            <span class="detail-value">
                <a href="mailto:<?= htmlspecialchars($contact['email']) ?>"><?= htmlspecialchars($contact['email']) ?></a>
            </span>
        </div>
        <?php endif; ?>

        <?php if ($contact['address'] !== ''): ?>
        <div class="detail-row">
            <span class="detail-label">Endereço</span>
            <span class="detail-value"><?= htmlspecialchars($contact['address']) ?></span>
        </div>
        <?php endif; ?>

        <?php if ($contact['notes'] !== ''): ?>
        <div class="detail-row">
            <span class="detail-label">Observações</span>
            <span class="detail-value"><?= nl2br(htmlspecialchars($contact['notes'])) ?></span>
        </div>
        <?php endif; ?>

        <div class="detail-row">
            <span class="detail-label">Cadastrado em</span>
            <span class="detail-value"><?= date('d/m/Y \à\s H:i', strtotime($contact['created_at'])) ?></span>
        </div>

        <!-- Ações disponíveis no detalhe -->
        <div class="form-actions" style="margin-top:20px;">
            <a href="edit.php?id=<?= $contact['id'] ?>" class="btn btn-primary">Editar</a>
            <a href="delete.php?id=<?= $contact['id'] ?>" class="btn btn-danger" data-confirm="Excluir <?= htmlspecialchars($contact['name']) ?>?">Excluir</a>
        </div>

    </div>

</main>

<footer>
    <div class="container">Agenda de Contatos &mdash; IFRO</div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
