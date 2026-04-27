<?php
require_once __DIR__ . '/php/data.php';

$page_title = 'Usuários';
$active_nav = 'usuarios';
$css_path   = 'css/style.css';

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']  ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nome === '' || $email === '') {
        $err = 'Nome e e-mail são obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = 'E-mail inválido.';
    } else {
        // Verifica duplicidade de e-mail
        $existentes = get_all_users();
        $dup = array_filter($existentes, fn($u) => strtolower($u['email']) === strtolower($email));
        if ($dup) {
            $err = 'Este e-mail já está cadastrado.';
        } elseif (save_user($nome, $email)) {
            $msg = "Usuário \"$nome\" cadastrado com sucesso!";
        } else {
            $err = 'Erro ao salvar. Verifique as permissões do diretório data/.';
        }
    }
}

$users = get_all_users();
include __DIR__ . '/layout/header.php';
?>

<div class="page-header">
  <div class="accent-line"></div>
  <h1>👤 Usuários</h1>
  <p>Cadastro de usuários do sistema (armazenados em TXT)</p>
</div>

<?php if ($msg): ?><div class="alert alert-success">✅ <?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger">❌ <?= htmlspecialchars($err) ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start">

  <!-- Formulário -->
  <div class="card">
    <div class="card-title">Novo Usuário</div>
    <form method="POST" action="usuarios.php">
      <div class="form-group">
        <label for="nome">Nome completo *</label>
        <input type="text" id="nome" name="nome" class="form-control"
               placeholder="Ex: João Silva"
               value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label for="email">E-mail *</label>
        <input type="email" id="email" name="email" class="form-control"
               placeholder="Ex: joao@empresa.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </div>
      <hr class="divider">
      <button type="submit" class="btn btn-primary">💾 Cadastrar</button>
    </form>
  </div>

  <!-- Lista -->
  <div class="card">
    <div class="card-title">Usuários Cadastrados
      <span class="tag"><?= count($users) ?></span>
    </div>

    <?php if (count($users) === 0): ?>
      <div class="empty-state" style="padding:30px 0">
        <div class="empty-icon">👤</div>
        <p>Nenhum usuário cadastrado.</p>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nome</th>
              <th>E-mail</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $i => $u): ?>
            <tr>
              <td style="color:var(--text-muted)"><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($u['nome']) ?></td>
              <td style="color:var(--text-muted)"><?= htmlspecialchars($u['email']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</div>

<?php include __DIR__ . '/layout/footer.php'; ?>

<style>
@media(max-width:768px){
  div[style*="grid-template-columns"]{grid-template-columns:1fr!important}
}
</style>
