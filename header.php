<?php

$page_title  = $page_title  ?? 'WaterFalls Game';
$active_nav  = $active_nav  ?? '';

$nav = [
    'home'              => ['icon' => '🏠', 'label' => 'Dashboard',               'href' => 'index.php'],
    'criar_multipla'    => ['icon' => '🔵', 'label' => 'Criar – Múltipla Escolha','href' => 'criar_multipla.php'],
    'criar_texto'       => ['icon' => '🟡', 'label' => 'Criar – Texto Livre',     'href' => 'criar_texto.php'],
    'listar'            => ['icon' => '📋', 'label' => 'Listar Perguntas',         'href' => 'listar.php'],
    'usuarios'          => ['icon' => '👤', 'label' => 'Usuários',                 'href' => 'usuarios.php'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?> – WaterFalls</title>
  <link rel="stylesheet" href="<?= $css_path ?? '../css/style.css' ?>">
</head>
<body>
<div class="wrapper">

  <button class="hamburger" onclick="document.querySelector('.sidebar').classList.toggle('open')">☰</button>

  <aside class="sidebar">
    <div class="sidebar-brand">
      <a href="index.php" class="logo-badge">
        <div class="logo-icon">🎮</div>
        <div>
          <div class="logo-text">WaterFalls</div>
          <div class="logo-sub">Corporate Game</div>
        </div>
      </a>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-section-label">Menu</div>
      <?php foreach ($nav as $key => $item): ?>
        <a href="<?= $item['href'] ?>"
           class="nav-item <?= $active_nav === $key ? 'active' : '' ?>">
          <span class="nav-icon"><?= $item['icon'] ?></span>
          <?= htmlspecialchars($item['label']) ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
      &copy; <?= date('Y') ?> WaterFalls Game
    </div>
  </aside>

  <main class="main">
