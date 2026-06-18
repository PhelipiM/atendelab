<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - AtendeLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Dashboard Provisório</h1>
                    <a href="?controller=auth&action=logout" class="btn btn-danger">Sair</a>
                </div>
                <p>Bem-vindo, <strong><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?></strong>!</p>
                <p>Seu perfil de acesso é: <span class="badge bg-secondary"><?= htmlspecialchars($usuario['perfil'], ENT_QUOTES, 'UTF-8') ?></span></p>
                <hr>
                <div class="mt-4">
                    <a href="?controller=usuarios&action=listar" class="btn btn-outline-primary" target="_blank">Testar rota protegida de usuários (JSON)</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>