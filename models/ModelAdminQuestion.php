<?php

class ModelAdminQuestion extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getQuestions(): array
    {
        $sql = "SELECT * FROM questions WHERE parent_id = 0 ORDER BY id DESC";
        $questions = $this->doSelect($sql);

        if (is_array($questions)) {
            foreach ($questions as $key => $q) {
                $sqlAnswer = "SELECT * FROM questions WHERE parent_id = ?";
                $answer = $this->doSelect($sqlAnswer, [(int)$q['id']], 'fetch');
                $questions[$key]['admin_answer'] = $answer ? $answer['content'] : '';
            }
        }
        return is_array($questions) ? $questions : [];
    }

    public function confirm(array $data): void
    {
        if (empty($data['id']) || !is_array($data['id'])) return;

        foreach ($data['id'] as $id) {
            $safeId = (int)$id;
            
            // Mettre à jour la question de l'utilisateur
            $questionText = $data['question_' . $safeId] ?? '';
            $answerText = $data['answer_' . $safeId] ?? '';

            // SÉCURITÉ : Utilisation de requêtes préparées pour éviter les injections SQL
            $this->doQuery("UPDATE questions SET content = ?, is_approved = 1 WHERE id = ?", [$questionText, $safeId]);

            // SÉCURITÉ : Gestion de la réponse de l'administrateur
            if (!empty($answerText)) {
                $sqlCheck = "SELECT id FROM questions WHERE parent_id = ?";
                $exists = $this->doSelect($sqlCheck, [$safeId]);

                if (!empty($exists)) {
                    // Mettre à jour la réponse existante
                    $this->doQuery("UPDATE questions SET content = ?, is_approved = 1 WHERE id = ?", [$answerText, $exists[0]['id']]);
                } else {
                    // Insérer une nouvelle réponse
                    $qInfo = $this->doSelect("SELECT product_id FROM questions WHERE id = ?", [$safeId], 'fetch');
                    $productId = (int)($qInfo['product_id'] ?? 0);
                    $createdAt = date('Y-m-d H:i:s');
                    
                    Model::sessionInit();
                    $adminId = (int)(Model::sessionGet('userId') ?? 1);

                    $this->doQuery("INSERT INTO questions (content, parent_id, product_id, user_id, created_at, is_approved) VALUES (?, ?, ?, ?, ?, 1)", [$answerText, $safeId, $productId, $adminId, $createdAt]);
                }
            }
        }
    }

    
}
?>