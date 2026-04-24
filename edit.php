<?php
require_once 'config/db.php';

// Valida o parâmetro id recebido na URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Busca o contato existente para pré-preencher o formulário
$stmt = mysqli_prepare($conn, "SELECT * FROM contacts WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result  = mysqli_stmt_get_result($stmt);
$contact = mysqli_fetch_assoc($result);

if (!$contact) {
    header('Location: index.php');
    exit;
}

$errors = [];
$input  = $contact; // começa com os dados atuais do banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lê e sanitiza os campos enviados
    $input['name']     = trim($_POST['name']     ?? '');
    $input['phone']    = trim($_POST['phone']    ?? '');
    $input['email']    = trim($_POST['email']    ?? '');
    $input['address']  = trim($_POST['address']  ?? '');
    $input['category'] = trim($_POST['category'] ?? 'personal');
    $input['notes']    = trim($_POST['notes']    ?? '');

    // Validações obrigatórias
    if ($input['name'] === '') {
        $errors[] = 'O nome é obrigatório.';
    }
    if ($input['phone'] === '') {
        $errors[] = 'O telefone é obrigatório.';
    }

    // Valida formato do e-mail se preenchido
    if ($input['email'] !== '' && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'E-mail inválido.';
    }

    // Valida se a categoria é um valor permitido
    $validCategories = ['personal', 'work', 'family', 'other'];
    if (!in_array($input['category'], $validCategories)) {
        $input['category'] = 'personal';
    }

    if (empty($errors)) {
        // Atualiza o contato no banco
        $stmt = mysqli_prepare($conn, "UPDATE contacts SET name=?, phone=?, email=?, address=?, category=?, notes=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'ssssssi',
            $input['name'],
            $input['phone'],
            $input['email'],
            $input['address'],
            $input['category'],
            $input['notes'],
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            // Redireciona para o detalhe após salvar
            header('Location: view.php?id=' . $id);
            exit;
        } else {
            $errors[] = 'Erro ao atualizar contato. Tente novamente.';
        }
    }
}

$categories = ['personal' => 'Pessoal', 'work' => 'Trabalho', 'family' => 'Família', 'other' => 'Outro'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar <?= htmlspecialchars($contact['name']) ?> — Agenda</title>
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
        <h2>Editar contato</h2>
        <a href="view.php?id=<?= $id ?>" class="btn btn-secondary btn-sm">&larr; Voltar</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="edit.php?id=<?= $id ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nome *</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($input['name']) ?>" required autofocus>
                </div>
                <div class="form-group">
                    <label for="phone">Telefone *</label>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($input['phone']) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($input['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="category">Categoria</label>
                    <select id="category" name="category">
                        <?php foreach ($categories as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $input['category'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Endereço</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($input['address'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="notes">Observações</label>
                <textarea id="notes" name="notes"><?= htmlspecialchars($input['notes'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Salvar alterações</button>
                <a href="view.php?id=<?= $id ?>" class="btn btn-secondary">Cancelar</a>
            </div>

        </form>
    </div>

</main>

<footer>
    <div class="container">Agenda de Contatos &mdash; IFRO</div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
