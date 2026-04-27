<?php
require_once __DIR__ . '/php/data.php';

$page_title = 'Listar Perguntas';
$active_nav = 'listar';
$css_path   = 'css/style.css';

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    $id = trim($_POST['excluir_id']);
    if (delete_question($id)) {
        $msg = 'Pergunta excluída com sucesso.';
    } else {
        $err = 'Não foi possível excluir a pergunta.';
    }
}

$questions = get_all_questions();


$filtro = $_GET['tipo'] ?? 'todos';
if ($filtro === 'multipla') {
    $questions = array_filter($questions, fn($q) => $q['tipo'] === 'multipla');
} elseif ($filtro === 'texto') {
    $questions = array_filter($questions, fn($q) => $q['tipo'] === 'texto');
}
$questions = array_values($questions);

include __DIR__ . '/layout/header.php';
?>

<div class="page-header">
  <div class="accent-line"></div>
  <h1>📋 Listar Perguntas</h1>
  <p>Gerencie todas as perguntas cadastradas</p>
</div>

<?php if ($msg): ?><div class="alert alert-success">✅ <?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger">❌ <?= htmlspecialchars($err) ?></div><?php endif; ?>


<div class="card" style="padding:16px 24px">
  <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
    <span style="font-size:13px;color:var(--text-muted)">Filtrar:</span>
    <a href="listar.php?tipo=todos"    class="btn btn-sm <?= $filtro==='todos'    ? 'btn-primary' : 'btn-secondary' ?>">Todos</a>
    <a href="listar.php?tipo=multipla" class="btn btn-sm <?= $filtro==='multipla' ? 'btn-primary' : 'btn-secondary' ?>">🔵 Múltipla Escolha</a>
    <a href="listar.php?tipo=texto"    class="btn btn-sm <?= $filtro==='texto'    ? 'btn-primary' : 'btn-secondary' ?>">🟡 Texto Livre</a>
    <span style="margin-left:auto;font-size:13px;color:var(--text-muted)"><?= count($questions) ?> pergunta(s)</span>
  </div>
</div>

<?php if (count($questions) === 0): ?>
<div class="card">
  <div class="empty-state">
    <div class="empty-icon">🎯</div>
    <p>Nenhuma pergunta encontrada.</p>
    <div class="btn-group" style="justify-content:center">
      <a href="criar_multipla.php" class="btn btn-primary">🔵 Criar Múltipla Escolha</a>
      <a href="criar_texto.php"    class="btn btn-warning">🟡 Criar Texto Livre</a>
    </div>
  </div>
</div>
<?php else: ?>
<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Enunciado</th>
          <th>Tipo</th>
          <th>Respostas</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($questions as $idx => $q): ?>
        <tr>
          <td style="color:var(--text-muted);width:40px"><?= $idx + 1 ?></td>
          <td>
            <a href="ver.php?id=<?= urlencode($q['id']) ?>"
               style="color:var(--text);text-decoration:none;font-weight:500">
              <?= htmlspecialchars(mb_substr($q['enunciado'], 0, 70)) ?>
              <?= mb_strlen($q['enunciado']) > 70 ? '…' : '' ?>
            </a>
          </td>
          <td>
            <?php if ($q['tipo'] === 'multipla'): ?>
              <span class="badge badge-multiple">🔵 Múltipla</span>
            <?php else: ?>
              <span class="badge badge-text">🟡 Texto</span>
            <?php endif; ?>
          </td>
          <td><?= count($q['respostas']) ?></td>
          <td>
            <div class="btn-group">
              <a href="ver.php?id=<?= urlencode($q['id']) ?>"
                 class="btn btn-secondary btn-sm">👁 Ver</a>

              <?php if ($q['tipo'] === 'multipla'): ?>
                <a href="editar_multipla.php?id=<?= urlencode($q['id']) ?>"
                   class="btn btn-warning btn-sm">✏️ Editar</a>
              <?php else: ?>
                <a href="editar_texto.php?id=<?= urlencode($q['id']) ?>"
                   class="btn btn-warning btn-sm">✏️ Editar</a>
              <?php endif; ?>

              <form method="POST" action="listar.php?tipo=<?= htmlspecialchars($filtro) ?>"
                    onsubmit="return confirm('Excluir esta pergunta e todas as suas respostas?')"
                    style="display:inline">
                <input type="hidden" name="excluir_id" value="<?= htmlspecialchars($q['id']) ?>">
                <button type="submit" class="btn btn-danger btn-sm">🗑 Excluir</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<div class="btn-group">
  <a href="criar_multipla.php" class="btn btn-primary">🔵 Nova Múltipla Escolha</a>
  <a href="criar_texto.php"    class="btn btn-warning">🟡 Nova Texto Livre</a>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
