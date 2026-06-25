<?php
require_once __DIR__ . '/app/controllers/UsuariosController.php';
require_once __DIR__ . '/app/controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Middleware/auth.php';
require_once __DIR__ . '/app/controllers/TiposAtendimentosController.php';
require_once __DIR__ . '/app/controllers/AtendimentosController.php';

// Define controller e action por query string.
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

switch ($controller) {
    case 'auth':
        $authController = new AuthController();
        switch ($action) {
            case 'login':
                $authController->exibirLogin();
                break;
            case 'entrar':
                $authController->entrar();
                break;
            case 'dashboard':
                $authController->dashboard();
                break;
            case 'logout':
                $authController->logout();
                break;
            default:
                http_response_code(404);
                echo 'Ação de autenticação não encontrada.';
                break;
        }
        break;

    case 'usuarios':
        exigirAutenticacao();
        $usuariosController = new UsuariosController();
        switch ($action) {
            case 'listar':
                $usuariosController->listar();
                break;
            case 'buscar':
                $usuariosController->buscarPorId();
                break;
            case 'criar':
                $usuariosController->criar();
                break;
            case 'atualizar':
                $usuariosController->atualizar();
                break;
            case 'excluir':
                $usuariosController->excluir();
                break;
            default:
                echo 'Ação de usuários não encontrada.';
                break;
        }
        break;

    case 'pessoas':
        exigirAutenticacao();
        $pessoasController = new PessoasController();
        switch ($action) {
            case 'listar':
                $pessoasController->listar();
                break;
            case 'buscar':
                $pessoasController->buscarPorId();
                break;
            case 'criar':
                $pessoasController->criar();
                break;
            case 'atualizar':
                $pessoasController->atualizar();
                break;
            case 'excluir':
                $pessoasController->excluir();
                break;
            default:
                echo 'Ação de pessoas não encontrada.';
                break;
        }
        break;

    case 'tipos':
        exigirAutenticacao();
        $tiposController = new TiposAtendimentosController();
        switch ($action) {
            case 'listar':
                $tiposController->listar();
                break;
            case 'buscar':
                $tiposController->buscarPorId();
                break;
            case 'criar':
                $tiposController->criar();
                break;
            case 'atualizar':
                $tiposController->atualizar();
                break;
            case 'excluir':
                $tiposController->excluir();
                break;
            default:
                echo 'Ação de tipos não encontrada.';
                break;
        }
        break;

    case 'atendimentos':
        exigirAutenticacao();
        $atendimentosController = new AtendimentosController();
        switch ($action) {
            case 'listar':
                $atendimentosController->listar();
                break;
            case 'buscar':
                $atendimentosController->buscarPorId();
                break;
            case 'criar':
                $atendimentosController->criar();
                break;
            case 'alterarStatus':
                $atendimentosController->alterarStatus();
                break;
            default:
                echo 'Ação de atendimentos não encontrada.';
                break;
        }
        break;

    default:
        http_response_code(404);
        echo 'Controller não encontrado.';
        break;
}