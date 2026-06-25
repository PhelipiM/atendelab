<?php
class AtendimentosController
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $sql = 'SELECT a.id, a.descricao, a.status, a.data_atendimento, a.horario_atendimento, a.observacao_final,
                       p.nome AS pessoa_nome, 
                       t.nome AS tipo_atendimento_nome, 
                       u.nome AS usuario_nome
                FROM atendimentos a
                INNER JOIN pessoas p ON a.pessoa_id = p.id
                INNER JOIN tipos_atendimentos t ON a.tipo_atendimento_id = t.id
                INNER JOIN usuarios u ON a.usuario_id = u.id
                ORDER BY a.id DESC';

        $stmt = $this->pdo->query($sql);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function buscarPorId(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $sql = 'SELECT a.*, p.nome AS pessoa_nome, t.nome AS tipo_atendimento_nome, u.nome AS usuario_nome 
                FROM atendimentos a
                INNER JOIN pessoas p ON a.pessoa_id = p.id
                INNER JOIN tipos_atendimentos t ON a.tipo_atendimento_id = t.id
                INNER JOIN usuarios u ON a.usuario_id = u.id
                WHERE a.id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $atendimento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$atendimento) {
            http_response_code(404);
            echo json_encode(['erro' => 'Atendimento não encontrado.'], JSON_UNESCAPED_UNICODE);
            return;
        }
        echo json_encode($atendimento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function criar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pessoa_id = filter_input(INPUT_POST, 'pessoa_id', FILTER_VALIDATE_INT);
        $tipo_atendimento_id = filter_input(INPUT_POST, 'tipo_atendimento_id', FILTER_VALIDATE_INT);
        $descricao = trim($_POST['descricao'] ?? '');
        
        $usuario_id = $_SESSION['usuario']['id'] ?? null; 

        if (!$pessoa_id || !$tipo_atendimento_id || $descricao === '' || !$usuario_id) {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados incompletos ou sessão inválida.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $sql = 'INSERT INTO atendimentos (pessoa_id, tipo_atendimento_id, usuario_id, descricao, data_atendimento, horario_atendimento, status) 
                    VALUES (:pessoa_id, :tipo_atendimento_id, :usuario_id, :descricao, CURDATE(), CURTIME(), "aberto")';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':pessoa_id', $pessoa_id, PDO::PARAM_INT);
            $stmt->bindValue(':tipo_atendimento_id', $tipo_atendimento_id, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->execute();

            http_response_code(201);
            echo json_encode(['mensagem' => 'Atendimento registrado com sucesso.', 'id' => $this->pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao registrar atendimento.'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function alterarStatus(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $status = $_POST['status'] ?? '';
        $observacao_final = trim($_POST['observacao_final'] ?? '');

        if (!$id || !in_array($status, ['aberto', 'em_andamento', 'concluido'], true)) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID ou Status inválido.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if ($status === 'concluido' && $observacao_final === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'Para concluir o atendimento, a observação final é obrigatória.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $sql = 'UPDATE atendimentos SET status = :status, observacao_final = :observacao_final WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':observacao_final', $observacao_final === '' ? null : $observacao_final);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['mensagem' => 'Status do atendimento atualizado com sucesso.'], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao alterar status.'], JSON_UNESCAPED_UNICODE);
        }
    }
}