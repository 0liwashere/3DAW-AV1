<?php
require_once __DIR__ . '/php/data.php';

$page_title = 'Dashboard';
$active_nav = 'home';
$css_path   = 'css/style.css';
include __DIR__ . '/layout/header.php';

$questions = get_all_questions();
$total     = count($questions);
$multipla  = count(array_filter($questions, fn($q) => $q['tipo'] === 'multipla'));
$texto     = count(array_filter($questions, fn($q) => $q['tipo'] === 'texto'));
$usuarios  = get_all_users();
?>

<div class="page-header">
  <div class="accent-line"></div>
  <h1>Dashboard</h1>
  <p>Visão geral do sistema de perguntas e respostas</p>
</div>

<div class="stats-row">
  <div class="stat-card">
    <div class="stat-number"><?= $total ?></div>
    <div class="stat-label">📋 Total de Perguntas</div>
  </div>
  <div class="stat-card">
    <div class="stat-number"><?= $multipla ?></div>
    <div class="stat-label">🔵 Múltipla Escolha</div>
  </div>
  <div class="stat-card">
    <div class="stat-number"><?= $texto ?></div>
    <div class="stat-label">🟡 Texto Livre</div>
  </div>
  <div class="stat-card">
    <div class="stat-number"><?= count($usuarios) ?></div>
    <div class="stat-label">👤 Usuários</div>
  </div>
</div>

<div class="card">
  <div class="card-title">⚡ Ações Rápidas</div>
  <div class="btn-group">
    <a href="criar_multipla.php" class="btn btn-primary">🔵 Nova Múltipla Escolha</a>
    <a href="criar_texto.php"    class="btn btn-warning">🟡 Nova Pergunta de Texto</a>
    <a href="listar.php"         class="btn btn-secondary">📋 Ver Todas</a>
    <a href="usuarios.php"       class="btn btn-secondary">👤 Usuários</a>
  </div>
</div>

<?php if ($total > 0): ?>
<div class="card">
  <div class="card-title">🕓 Últimas Perguntas</div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Enunciado</th>
          <th>Tipo</th>
          <th>Respostas</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (array_slice(array_reverse($questions), 0, 5) as $q): ?>
        <tr>
          <td><?= htmlspecialchars(mb_substr($q['enunciado'], 0, 60)) ?>
              <?= mb_strlen($q['enunciado']) > 60 ? '…' : '' ?>
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
              <a href="ver.php?id=<?= urlencode($q['id']) ?>"     class="btn btn-secondary btn-sm">👁 Ver</a>
              <a href="<?= $q['tipo'] === 'multipla' ? 'editar_multipla.php' : 'editar_texto.php' ?>?id=<?= urlencode($q['id']) ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php else: ?>
<div class="card">
  <div class="empty-state">
    <div class="empty-icon">🎯</div>
    <p>Nenhuma pergunta cadastrada ainda.</p>
    <a href="criar_multipla.php" class="btn btn-primary">Criar primeira pergunta</a>
  </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/layout/footer.php'; ?>
