<?php
require_once __DIR__ . '/php/data.php';

$id = trim($_GET['id'] ?? '');
$q  = $id ? get_question_by_id($id) : null;

if (!$q) {
    header('Location: listar.php');
    exit;
}

$page_title = 'Visualizar Pergunta';
$active_nav = 'listar';
$css_path   = 'css/style.css';

include __DIR__ . '/layout/header.php';

$letters = ['A','B','C','D','E'];
?>

<div class="page-header">
  <div class="accent-line"></div>
  <h1>👁 Visualizar Pergunta</h1>
  <p>Detalhes completos da pergunta selecionada</p>
</div>

<div class="question-detail">
  <div class="question-detail-header">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
      <div>
        <?php if ($q['tipo'] === 'multipla'): ?>
          <span class="badge badge-multiple" style="margin-bottom:10px">🔵 Múltipla Escolha</span>
        <?php else: ?>
          <span class="badge badge-text" style="margin-bottom:10px">🟡 Texto Livre</span>
        <?php endif; ?>
        <h2 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:700;line-height:1.4">
          <?= htmlspecialchars($q['enunciado']) ?>
        </h2>
      </div>
      <div class="btn-group">
        <?php if ($q['tipo'] === 'multipla'): ?>
          <a href="editar_multipla.php?id=<?= urlencode($q['id']) ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
        <?php else: ?>
          <a href="editar_texto.php?id=<?= urlencode($q['id']) ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
        <?php endif; ?>
        <a href="listar.php" class="btn btn-secondary btn-sm">← Voltar</a>
      </div>
    </div>
  </div>

  <div class="question-detail-body">
    <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;font-weight:600;letter-spacing:.06em;text-transform:uppercase">
      <?= $q['tipo'] === 'multipla' ? 'Alternativas' : 'Resposta Esperada' ?>
    </p>

    <?php if ($q['tipo'] === 'multipla'): ?>
      <ul class="answer-list">
        <?php foreach ($q['respostas'] as $idx => $r): ?>
        <li>
          <div class="letter-chip"><?= $letters[$idx] ?? ($idx + 1) ?></div>
          <span><?= htmlspecialchars($r) ?></span>
        </li>
        <?php endforeach; ?>
      </ul>

    <?php else: ?>
      <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:18px;line-height:1.7">
        <?= nl2br(htmlspecialchars($q['respostas'][0] ?? '')) ?>
      </div>
    <?php endif; ?>

    <hr class="divider">

    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <form method="POST" action="listar.php"
            onsubmit="return confirm('Excluir esta pergunta e suas respostas?')">
        <input type="hidden" name="excluir_id" value="<?= htmlspecialchars($q['id']) ?>">
        <button type="submit" class="btn btn-danger">🗑 Excluir Pergunta</button>
      </form>
      <a href="listar.php" class="btn btn-secondary">← Voltar à lista</a>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
