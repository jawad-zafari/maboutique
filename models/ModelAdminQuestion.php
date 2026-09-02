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

   
}
?>