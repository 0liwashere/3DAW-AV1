<?php
require_once __DIR__ . '/php/data.php';

$id = trim($_GET['id'] ?? $_POST['id'] ?? '');
$q  = $id ? get_question_by_id($id) : null;

if (!$q || $q['tipo'] !== 'texto') {
    header('Location: listar.php');
    exit;
}

$page_title = 'Editar – Texto Livre';
$active_nav = 'listar';
$css_path   = 'css/style.css';

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $enunciado = trim($_POST['enunciado'] ?? '');
    $resposta  = trim($_POST['resposta']  ?? '');

    if ($enunciado === '') {
        $err = 'O enunciado é obrigatório.';
    } elseif ($resposta === '') {
        $err = 'A resposta esperada é obrigatória.';
    } else {
        $updated = [
            'id'        => $q['id'],
            'tipo'      => 'texto',
            'enunciado' => $enunciado,
            'respostas' => [$resposta],
        ];
        if (update_question($updated)) {
            $q   = $updated;
            $msg = 'Pergunta atualizada com sucesso!';
        } else {
            $err = 'Erro ao atualizar. Tente novamente.';
        }
    }
}

$enunciado_val = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ($_POST['enunciado'] ?? $q['enunciado'])
    : $q['enunciado'];

$resposta_val = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ($_POST['resposta'] ?? ($q['respostas'][0] ?? ''))
    : ($q['respostas'][0] ?? '');

include __DIR__ . '/layout/header.php';
?>

<div class="page-header">
  <div class="accent-line"></div>
  <h1>✏️ Editar Pergunta – Texto Livre</h1>
  <p>Altere o enunciado ou a resposta esperada</p>
</div>

<?php if ($msg): ?><div class="alert alert-success">✅ <?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger">❌ <?= htmlspecialchars($err) ?></div><?php endif; ?>

<div class="card">
  <div class="card-title">Editar Pergunta
    <span class="tag" style="background:rgba(245,158,11,.12);color:var(--accent3);border-color:rgba(245,158,11,.2)">
      Texto Livre
    </span>
  </div>

  <form method="POST" action="editar_texto.php">
    <input type="hidden" name="id"    value="<?= htmlspecialchars($q['id']) ?>">
    <input type="hidden" name="salvar" value="1">

    <div class="form-group">
      <label for="enunciado">Enunciado *</label>
      <textarea id="enunciado" name="enunciado" class="form-control" rows="3"
                required><?= htmlspecialchars($enunciado_val) ?></textarea>
    </div>

    <div class="form-group">
      <label for="resposta">Resposta Esperada *</label>
      <textarea id="resposta" name="resposta" class="form-control" rows="5"><?= htmlspecialchars($resposta_val) ?></textarea>
    </div>

    <hr class="divider">
    <div class="btn-group">
      <button type="submit" class="btn btn-primary">💾 Salvar Alterações</button>
      <a href="ver.php?id=<?= urlencode($q['id']) ?>" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
