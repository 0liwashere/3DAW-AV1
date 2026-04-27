<?php
require_once __DIR__ . '/php/data.php';

$page_title = 'Criar – Múltipla Escolha';
$active_nav = 'criar_multipla';
$css_path   = 'css/style.css';

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enunciado = trim($_POST['enunciado'] ?? '');
    $opcoes    = $_POST['opcao']   ?? [];
    $opcoes    = array_map('trim', $opcoes);
    $opcoes    = array_values(array_filter($opcoes, fn($o) => $o !== ''));

    if ($enunciado === '') {
        $err = 'O enunciado é obrigatório.';
    } elseif (count($opcoes) < 2) {
        $err = 'Informe pelo menos 2 opções de resposta.';
    } else {
        $q = [
            'id'        => generate_id(),
            'tipo'      => 'multipla',
            'enunciado' => $enunciado,
            'respostas' => $opcoes,
        ];
        if (save_question($q)) {
            $msg = 'Pergunta criada com sucesso!';
        } else {
            $err = 'Erro ao salvar. Verifique as permissões do diretório data/.';
        }
    }
}

include __DIR__ . '/layout/header.php';
?>

<div class="page-header">
  <div class="accent-line"></div>
  <h1>🔵 Criar Pergunta – Múltipla Escolha</h1>
  <p>Cadastre um enunciado com até 5 alternativas</p>
</div>

<?php if ($msg): ?><div class="alert alert-success">✅ <?= htmlspecialchars($msg) ?> <a href="listar.php">Ver lista</a></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger">❌ <?= htmlspecialchars($err) ?></div><?php endif; ?>

<div class="card">
  <div class="card-title">Nova Pergunta <span class="tag">Múltipla Escolha</span></div>

  <form method="POST" action="criar_multipla.php">

    <div class="form-group">
      <label for="enunciado">Enunciado da Pergunta *</label>
      <textarea id="enunciado" name="enunciado" class="form-control"
                placeholder="Digite a pergunta aqui…" rows="3"
                required><?= htmlspecialchars($_POST['enunciado'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label>Opções de Resposta * <small style="color:var(--text-muted)">(mín. 2, máx. 5)</small></label>
      <div class="answers-block">
        <?php
        $letters = ['A','B','C','D','E'];
        for ($i = 0; $i < 5; $i++):
          $val = htmlspecialchars($_POST['opcao'][$i] ?? '');
        ?>
        <div class="answer-row">
          <div class="answer-letter"><?= $letters[$i] ?></div>
          <input type="text"
                 name="opcao[]"
                 class="form-control"
                 placeholder="Opção <?= $letters[$i] ?><?= $i < 2 ? ' (obrigatório)' : '' ?>"
                 value="<?= $val ?>"
                 <?= $i < 2 ? 'required' : '' ?>>
        </div>
        <?php endfor; ?>
      </div>
    </div>

    <hr class="divider">
    <div class="btn-group">
      <button type="submit" class="btn btn-primary">💾 Salvar Pergunta</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>

  </form>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
