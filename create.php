<?php
require_once 'config/db.php';

$errors = [];
$input  = ['name' => '', 'phone' => '', 'email' => '', 'address' => '', 'category' => 'personal', 'notes' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lê e sanitiza os campos do formulário
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
        // Insere o novo contato usando prepared statement
        $stmt = mysqli_prepare($conn, "INSERT INTO contacts (name, phone, email, address, category, notes) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssssss',
            $input['name'],
            $input['phone'],
            $input['email'],
            $input['address'],
            $input['category'],
            $input['notes']
        );

        if (mysqli_stmt_execute($stmt)) {
            // Redireciona para a lista após cadastro bem-sucedido
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Erro ao salvar contato. Tente novamente.';
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
    <title>Novo Contato — Agenda</title>
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
        <h2>Novo contato</h2>
        <a href="index.php" class="btn btn-secondary btn-sm">&larr; Voltar</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $err): ?>
                <div><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="create.php">

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
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($input['email']) ?>">
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
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($input['address']) ?>">
            </div>

            <div class="form-group">
                <label for="notes">Observações</label>
                <textarea id="notes" name="notes"><?= htmlspecialchars($input['notes']) ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Salvar contato</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
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
