<?php
require_once __DIR__ . '/../config.php'; // Importa routes.php

// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['erro'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['erro']) . '</div>';
    unset($_SESSION['erro']);
}
if (isset($_SESSION['sucesso'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['sucesso']) . '</div>';
    unset($_SESSION['sucesso']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Petch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/styleCadastro.css">
</head>
<body>
    <div><?php require ROOT_PATH . '/app/views/header.php'; ?></div>
    <div class="split-layout">
        <!-- Coluna Alternada (Esquerda) -->
    <div class="alternate-column">
        <!-- Item 1 - Imagem|Texto -->
        <div class="alternate-item">
            <div class="alternate-image">
                <img src="<?= BASE_PATH ?>/images/imgMosaico1.png" alt="Ame">
            </div>
            <div class="alternate-text">
                <h3>Ame</h3>
            </div>
        </div>

        <!-- Item 2 - Texto|Imagem -->
        <div class="alternate-item reverse">
            <div class="alternate-image">
                <img src="<?= BASE_PATH ?>/images/imgMosaico2.png" alt="Faça">
            </div>
            <div class="alternate-text">
                <h3>Faça</h3>
            </div>
        </div>

        <!-- Item 3 - Imagem|Texto -->
        <div class="alternate-item">
            <div class="alternate-image">
                <img src="<?= BASE_PATH ?>/images/imgMosaico3.png" alt="Adote">
            </div>
            <div class="alternate-text">
                <h3>Adote</h3>
            </div>
        </div>

        <!-- Item 4 - Texto|Imagem -->
        <div class="alternate-item reverse">
            <div class="alternate-image">
                <img src="<?= BASE_PATH ?>/images/imgMosaico4.jpg" alt="Proteja">
            </div>
            <div class="alternate-text">
                <h3>Proteja</h3>
            </div>
        </div>

        <!-- Item 5 - Imagem|Texto -->
        <div class="alternate-item">
            <div class="alternate-image">
                <img src="<?= BASE_PATH ?>/images/imgMosaico5.jpg" alt="Colabore">
            </div>
            <div class="alternate-text">
                <h3>Colabore</h3>
            </div>
        </div>

        <!-- Item 6 - Texto|Imagem -->
        <div class="alternate-item reverse">
            <div class="alternate-image">
                <img src="<?= BASE_PATH ?>/images/imgMosaico6.jpg" alt="Salve">
            </div>
            <div class="alternate-text">
                <h3>Salve</h3>
            </div>
        </div>
    </div>

        <!-- Coluna do Formulário (Direita) -->
    <div class="form-column">
        <div class="auth-container fade-in">
            <h1 class="text-center mb-4">Novo usuário? Faça seu cadastro.</h1>
            <form action="<?= CONTROLLERS_PATH ?>/UserController.php" method="POST" class="w-100">
            <input type="hidden" name="acao" value="cadastrar">
            
            <!-- Linha 1: Nome Completo -->
            <div class="form-row">
                <div class="mb-3">
                    <label class="form-label">Seu nome*</label>
                    <input type="text" name="nome" class="form-control" required>
                    <input type="hidden" name="tipo" value="usuario">
                </div>
            </div>

            <!-- Linha 2: Email e Confirmação de Email -->
            <div class="form-row">
                <div class="mb-3">
                    <label class="form-label">Email*</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmação de e-mail*</label>
                    <input type="email" name="confirmacao_email" class="form-control" required>
                </div>
            </div>

            <!-- Linha 3: Telefone e Celular -->
            <div class="form-row">
                <div class="mb-3">
                    <label class="form-label">Telefone*</label>
                    <input type="tel" name="telefone" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Celular*</label>
                    <input type="tel" name="celular" class="form-control">
                </div>
            </div>

            <!-- Linha 4: CPF e CNPJ -->
            <div class="form-row">
                <div class="mb-3">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control" placeholder="Somente números">
                </div>
                <div class="mb-3">
                    <label class="form-label">CNPJ</label>
                    <input type="text" name="cnpj" class="form-control" placeholder="Somente números">
                </div>
            </div>

            <!-- Linha 6: CEP e Endereço -->
            <div class="form-row">
                <div class="mb-3">
                    <label class="form-label">CEP*</label>
                    <input type="text" id="cep" name="cep" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Endereço*</label>
                    <input type="text" id="endereco" name="endereco" class="form-control">
                </div>
                <div class="mb-3">
            <label class="form-label">Bairro*</label>
            <input type="text" id="bairro" name="bairro" class="form-control">
        </div>
            </div>

            <!-- Linha 5: Estado e Cidade -->
            <div class="form-row">
            <div class="mb-3">
                <label class="form-label">Estado*</label>
                <select name="estado" id="estado" class="form-control">
                <!-- Estados já preenchidos acima -->
                <option value="">Selecione o Estado</option>
                <option value="AC">Acre</option>
                <option value="AL">Alagoas</option>
                <option value="AP">Amapá</option>
                <option value="AM">Amazonas</option>
                <option value="BA">Bahia</option>
                <option value="CE">Ceará</option>
                <option value="DF">Distrito Federal</option>
                <option value="ES">Espírito Santo</option>
                <option value="GO">Goiás</option>
                <option value="MA">Maranhão</option>
                <option value="MT">Mato Grosso</option>
                <option value="MS">Mato Grosso do Sul</option>
                <option value="MG">Minas Gerais</option>
                <option value="PA">Pará</option>
                <option value="PB">Paraíba</option>
                <option value="PR">Paraná</option>
                <option value="PE">Pernambuco</option>
                <option value="PI">Piauí</option>
                <option value="RJ">Rio de Janeiro</option>
                <option value="RN">Rio Grande do Norte</option>
                <option value="RS">Rio Grande do Sul</option>
                <option value="RO">Rondônia</option>
                <option value="RR">Roraima</option>
                <option value="SC">Santa Catarina</option>
                <option value="SP">São Paulo</option>
                <option value="SE">Sergipe</option>
                <option value="TO">Tocantins</option>
                </select>


                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Cidade*</label>
                <input type="text" id="cidade" name="cidade" class="form-control">
            </div>
            </div>   

            <!-- Linha 7: Número e Complemento -->
            <div class="form-row">
                <div class="mb-3">
                    <label class="form-label">Número*</label>
                    <input type="text" name="numero" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Complemento</label>
                    <input type="text" name="complemento" class="form-control" placeholder="Casa ou apartamento">
                </div>
            </div>

            <!-- Linha 8: Senha e Confirmação de Senha -->
            <div class="form-row">
                <div class="mb-3">
                    <label class="form-label">Senha*</label>
                    <input type="password" name="senha" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmação de senha*</label>
                    <input type="password" name="confirmacao_senha" class="form-control" required>
                </div>
            </div>

            <!-- Termos e Privacidade -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="termos" required>
                <label class="form-check-label" for="termos">
                    Ao clicar no botão você estará automaticamente concordando com os nossos <a href="#">Termo de uso</a> e <a href="#">Política de Privacidade</a> do site Petch.
                </label>
            </div>

            <!-- Botão de Cadastro -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn-custom">Salvar</button>
            </div>

            <!-- Links inferiores -->
            <div class="mt-3 text-center">
                <p>
                    <a href="<?= PUBLIC_PATH ?>/login.php" class="text-decoration-none link-custom">Já tem conta?</a> |
                    <a href="<?= PUBLIC_PATH ?>/RedefinirSenha.php" class="text-decoration-none link-custom">Esqueceu sua senha?</a> |
                    <a href="<?= PUBLIC_PATH ?>/reenviar-confirmacao.php" class="text-decoration-none link-custom">Não confirmou sua conta?</a>
                </p>
            </div>
        </form>
    </div>
    <?php include ROOT_PATH . '/app/views/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?= JS_PATH ?>/script.js"></script>

</body>
</html>