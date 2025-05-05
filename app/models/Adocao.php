<?php

class FormAdocao
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function salvar($dados)
    {
        $sql = "INSERT INTO formulario_adocao (
                    nome, email, telefone, endereco, tipo_moradia,
                    tela_protecao, condominio_permite, espaco_suficiente,
                    condicoes_financeiras, compromisso
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['email'],
            $dados['telefone'],
            $dados['endereco'],
            $dados['tipo_moradia'],
            $dados['tela_protecao'],
            $dados['condominio_permite'],
            $dados['espaco_suficiente'],
            $dados['condicoes_financeiras'],
            $dados['compromisso']
        ]);
    }
}
