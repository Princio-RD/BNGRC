<?php
$pageTitle = $pageTitle ?? 'APK GNBRC';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

  <header class="header">
    <div class="header-title">
      <h1>APK GNBRC</h1>
    </div>
    <div class="header-subtitle">
      <h2>Mode Urgent</h2>
    </div>
  </header>

  <div class="content">
    <aside class="panel-gauche">
      <ul class="menu">
        <li class="menu-item"><a href="/">Tableau de bord</a></li>
        <li class="menu-item"><a href="/besoin">Besoin</a></li>
        <li class="menu-item"><a href="/dons">Dons disponible</a></li>
        <li class="menu-item"><a href="/ville">Ville</a></li>
        <li class="menu-item"><a href="/besoin/achats">Achats besoins</a></li>
        <li class="menu-item"><a href="/besoin/simulation">Simulation achats</a></li>
        <li class="menu-item"><a href="/recap">Récapitulatif</a></li>
      </ul>
    </aside>