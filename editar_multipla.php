<?php
require_once __DIR__ . '/php/data.php';

$id = trim($_GET['id'] ?? $_POST['id'] ?? '');
$q  = $id ? get_question_by_id($id) : null;

if (!$q || $q['tipo'] !== 'multipla') {
    header('Location: listar.php');
    exit;
}

$page_title = 'Editar – Múltipla Escolha';
$active_nav = 'listar';
$css_path   = 'css/style.css';

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $enunciado = trim($_POST['enunciado'] ?? '');
    $opcoes    = $_POST['opcao'] ?? [];
    $opcoes    = array_map('trim', $opcoes);
    $opcoes    = array_values(array_filter($opcoes, fn($o) => $o !== ''));

    if ($enunciado === '') {
        $err = 'O enunciado é obrigatório.';
    } elseif (count($opcoes) < 2) {
        $err = 'Informe pelo menos 2 opções de resposta.';
    } else {
        $updated = [
            'id'        => $q['id'],
            'tipo'      => 'multipla',
            'enunciado' => $enunciado,
            'respostas' => $opcoes,
        ];
        if (update_question($updated)) {
            $q   = $updated;
            $msg = 'Pergunta atualizada com sucesso!';
        } else {
            $err = 'Erro ao atualizar. Tente novamente.';
        }
    }
}

include __DIR__ . '/layout/header.php';

$letters = ['A','B','C','D','E'];

$vals = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ($_POST['opcao'] ?? [])
    : $q['respostas'];

while (count($vals) < 5) $vals[] = '';
?>

<div class="page-header">
  <div class="accent-line"></div>
  <h1>✏️ Editar Pergunta – Múltipla Escolha</h1>
  <p>Altere o enunciado ou as alternativas</p>
</div>

<?php if ($msg): ?><div class="alert alert-success">✅ <?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger">❌ <?= htmlspecialchars($err) ?></div><?php endif; ?>

<div class="card">
  <div class="card-title">Editar Pergunta <span class="tag">Múltipla Escolha</span></div>

  <form method="POST" action="editar_multipla.php">
    <input type="hidden" name="id"    value="<?= htmlspecialchars($q['id']) ?>">
    <input type="hidden" name="salvar" value="1">

    <div class="form-group">
      <label for="enunciado">Enunciado *</label>
      <textarea id="enunciado" name="enunciado" class="form-control" rows="3" required><?=
        htmlspecialchars(
          $_SERVER['REQUEST_METHOD'] === 'POST'
            ? ($_POST['enunciado'] ?? $q['enunciado'])
            : $q['enunciado']
        )
      ?></textarea>
    </div>

    <div class="form-group">
      <label>Opções de Resposta * <small style="color:var(--text-muted)">(mín. 2, máx. 5)</small></label>
      <div class="answers-block">
        <?php for ($i = 0; $i < 5; $i++): ?>
        <div class="answer-row">
          <div class="answer-letter"><?= $letters[$i] ?></div>
          <input type="text"
                 name="opcao[]"
                 class="form-control"
                 placeholder="Opção <?= $letters[$i] ?><?= $i < 2 ? ' (obrigatório)' : '' ?>"
                 value="<?= htmlspecialchars($vals[$i]) ?>"
                 <?= $i < 2 ? 'required' : '' ?>>
        </div>
        <?php endfor; ?>
      </div>
    </div>

    <hr class="divider">
    <div class="btn-group">
      <button type="submit" class="btn btn-primary">💾 Salvar Alterações</button>
      <a href="ver.php?id=<?= urlencode($q['id']) ?>" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
