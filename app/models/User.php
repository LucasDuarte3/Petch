<?php
class User {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Cadastra um novo usuário com token de confirmação
     * @param array $dados Dados do usuário [nome, email, senha, telefone, etc...]
     * @return int|false ID do usuário criado ou false em caso de erro
     */
    public function createWithToken(array $dados) {
        try {
            // Validação básica dos campos obrigatórios
            if (empty($dados['email']) || empty($dados['senha']) || empty($dados['nome'])) {
                throw new Exception("Campos obrigatórios faltando!");
            }

            // Gera token e data de expiração (24 horas)
            $token = $dados['token_verificacao']; // Já vem do controller
            $token_reset = $dados['token_reset'] ?? null;


            $sql = "INSERT INTO usuarios (
                nome, email, senha, telefone, endereco,
                cpf_cnpj, tipo, token_verificacao, verificado, token_reset
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $dados['nome'],
                $dados['email'],
                password_hash($dados['senha'], PASSWORD_DEFAULT),
                $dados['telefone'] ?? null,
                $dados['endereco'] ?? null,
                $dados['cpf_cnpj'] ?? null,
                $dados['tipo'] ?? 'usuario',
                $token,
                0, // Não verificado inicialmente
                $token_reset
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            
            // Trata erros específicos de duplicata
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                if (strpos($e->getMessage(), 'email') !== false) {
                    throw new Exception("Este e-mail já está cadastrado!");
                }
                if (strpos($e->getMessage(), 'cpf_cnpj') !== false) {
                    throw new Exception("Este CPF/CNPJ já está cadastrado!");
                }
            }
            
            throw new Exception("Erro ao cadastrar usuário. Tente novamente.");
        }
    }

    /**
     * Verifica e ativa conta com token
     * @param string $token Token de confirmação
     * @return bool Sucesso da operação
     */
    public function verifyEmail($token) {
        try {
            // Primeiro verifica se o token é válido e não expirou
            $sql = "SELECT id FROM usuarios WHERE token_verificacao = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$token]);
            
            if ($stmt->rowCount() === 0) {
                return false;
            }

            // Atualiza o usuário como confirmado
            $sql = "UPDATE usuarios SET 
                    verificado = 1,
                    token_verificacao = NULL
                    WHERE token_verificacao = ?";
                    
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$token]);
        } catch (PDOException $e) {
            error_log("Erro ao verificar email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se e-mail já está cadastrado
     * @param string $email Email a verificar
     * @return bool
     */
    public function emailExists($email) {
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    /**
     * Verifica credenciais de login
     * @param string $email
     * @param string $senha
     * @return array|false Dados do usuário ou false se inválido
     */
    public function verifyCredentials($email, $senha) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            return $user;
        }
        return false;
    }

    /**
     * Busca usuário por email
     * @param string $email
     * @return array|false
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica se CPF/CNPJ já existe
     * @param string $documento
     * @return bool
     */
    public function documentExists($documento) {
        $documento = preg_replace('/[^0-9]/', '', $documento);
        $sql = "SELECT id FROM usuarios WHERE cpf_cnpj = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$documento]);
        return (bool) $stmt->fetch();
    }
    
    /**
    * Busca usuário por ID
    */
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Gera token para redefinição de senha
     * @param string $email
     * @return string|false Token gerado ou false em caso de erro
     */
    public function generatePasswordResetToken($email) {
        try {
            // Verifica se o e-mail existe
            $user = $this->findByEmail($email);
            if (!$user) {
                throw new Exception("E-mail não cadastrado.");
            }

            // Gera token e data de expiração (1 hora)
            $token = bin2hex(random_bytes(32));

            // Atualiza no banco
            $sql = "UPDATE usuarios SET 
                    token_reset = ?
                    WHERE email = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$token, $email]);

            return $token;
        } catch (PDOException $e) {
            error_log("Erro ao gerar token: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Redefine a senha com base no token válido
     * @param string $token
     * @param string $novaSenha
     * @return bool Sucesso da operação
     */
    public function resetPassword($token, $novaSenha) {
        try {
            // Verifica se o token é válido e não expirou
            $sql = "SELECT id FROM usuarios 
                    WHERE token_reset = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$token]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Token inválido ou expirado!");
            }

            // Atualiza a senha e limpa o token
            $sql = "UPDATE usuarios SET 
                    senha = ?,
                    token_reset = NULL
                    WHERE token_reset = ?";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                password_hash($novaSenha, PASSWORD_DEFAULT),
                $token
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao redefinir senha: " . $e->getMessage());
            return false;
        }
    }

}
?>