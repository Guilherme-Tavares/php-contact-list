<?php
require_once 'config/db.php';

// Valida o parâmetro id recebido na URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Verifica se o contato existe antes de excluir
$stmt = mysqli_prepare($conn, "SELECT id, name FROM contacts WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result  = mysqli_stmt_get_result($stmt);
$contact = mysqli_fetch_assoc($result);

if (!$contact) {
    header('Location: index.php');
    exit;
}

// A exclusão só ocorre após confirmação via POST (enviado pelo JS)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = mysqli_prepare($conn, "DELETE FROM contacts WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    // Redireciona para a lista após exclusão
    header('Location: index.php');
    exit;
}

// Exibe página de confirmação caso o JS esteja desabilitado
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir contato — Agenda</title>
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
        <h2>Excluir contato</h2>
        <a href="index.php" class="btn btn-secondary btn-sm">&larr; Voltar</a>
    </div>

    <!-- Fallback de confirmação para quando o JS está desabilitado -->
    <div class="form-card" style="text-align:center;">
        <p style="font-size:1rem;margin-bottom:20px;">
            Tem certeza que deseja excluir <strong><?= htmlspecialchars($contact['name']) ?></strong>?<br>
            <span style="color:var(--text-muted);font-size:.88rem;">Essa ação não pode ser desfeita.</span>
        </p>
        <form method="POST" action="delete.php?id=<?= $id ?>" class="form-actions" style="justify-content:center;">
            <button type="submit" class="btn btn-danger">Sim, excluir</button>
            <a href="view.php?id=<?= $id ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

</main>

<footer>
    <div class="container">Agenda de Contatos &mdash; IFRO</div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
