<?php
require_once __DIR__ . '/php/data.php';

$page_title = 'Criar – Texto Livre';
$active_nav = 'criar_texto';
$css_path   = 'css/style.css';

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enunciado = trim($_POST['enunciado'] ?? '');
    $resposta  = trim($_POST['resposta']  ?? '');

    if ($enunciado === '') {
        $err = 'O enunciado é obrigatório.';
    } elseif ($resposta === '') {
        $err = 'A resposta esperada é obrigatória.';
    } else {
        $q = [
            'id'        => generate_id(),
            'tipo'      => 'texto',
            'enunciado' => $enunciado,
            'respostas' => [$resposta],
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
  <h1>🟡 Criar Pergunta – Texto Livre</h1>
  <p>Cadastre uma pergunta com resposta dissertativa</p>
</div>

<?php if ($msg): ?><div class="alert alert-success">✅ <?= htmlspecialchars($msg) ?> <a href="listar.php">Ver lista</a></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger">❌ <?= htmlspecialchars($err) ?></div><?php endif; ?>

<div class="card">
  <div class="card-title">Nova Pergunta <span class="tag" style="background:rgba(245,158,11,.12);color:var(--accent3);border-color:rgba(245,158,11,.2)">Texto Livre</span></div>

  <form method="POST" action="criar_texto.php">

    <div class="form-group">
      <label for="enunciado">Enunciado da Pergunta *</label>
      <textarea id="enunciado" name="enunciado" class="form-control"
                placeholder="Digite a pergunta aqui…" rows="3"
                required><?= htmlspecialchars($_POST['enunciado'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
      <label for="resposta">Resposta Esperada *</label>
      <textarea id="resposta" name="resposta" class="form-control"
                placeholder="Descreva a resposta esperada ou gabarito…" rows="4"
                required><?= htmlspecialchars($_POST['resposta'] ?? '') ?></textarea>
    </div>

    <hr class="divider">
    <div class="btn-group">
      <button type="submit" class="btn btn-primary">💾 Salvar Pergunta</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>

  </form>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
