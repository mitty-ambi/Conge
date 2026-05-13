<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<?php $active = 'dashboard'; ?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_admin') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Vue d'ensemble</div>
        <div class="topbar-breadcrumb">Administration</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('admin/employes') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter un employé</a>
      </div>
    </div>

    <div class="content">
      <?= $this->include('partials/flash') ?>

      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div>
          <div class="metric-val"><?= esc($metrics['employees'] ?? 0) ?></div>
          <div class="metric-label">Employés actifs</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val"><?= esc($metrics['pending'] ?? 0) ?></div>
          <div class="metric-label">Demandes en attente</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val"><?= esc($metrics['approved_month'] ?? 0) ?></div>
          <div class="metric-label">Approuvées ce mois</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div>
          <div class="metric-val"><?= esc($metrics['departments'] ?? 0) ?></div>
          <div class="metric-label">Départements</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-person-slash"></i></div></div>
          <div class="metric-val"><?= esc($metrics['absents'] ?? 0) ?></div>
          <div class="metric-label">Absents aujourd'hui</div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">
        <div class="data-card" style="margin:0">
          <div class="data-card-head">
            <h3>Demandes récentes</h3>
            <a href="<?= site_url('rh/demandes') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
          </div>
          <table class="tbl">
            <thead>
              <tr><th>Employé</th><th>Type</th><th>Durée</th><th>Statut</th></tr>
            </thead>
            <tbody>
              <?php if (empty($recent)): ?>
                <tr><td colspan="4"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande récente.</p></div></td></tr>
              <?php else: ?>
                <?php foreach ($recent as $demande): ?>
                  <?php
                    $typeClass = 't-annuel';
                    $libelle = mb_strtolower((string) $demande['libelle']);
                    if (str_contains($libelle, 'maladie')) {
                      $typeClass = 't-maladie';
                    } elseif (str_contains($libelle, 'special')) {
                      $typeClass = 't-special';
                    }
                  ?>
                  <tr>
                    <td><div style="display:flex;align-items:center;gap:7px"><div class="avatar av-green" style="width:28px;height:28px;font-size:.62rem"><?= esc(strtoupper(substr($demande['prenom'], 0, 1) . substr($demande['nom'], 0, 1))) ?></div><span class="td-name" style="font-size:.84rem"><?= esc($demande['prenom'] . ' ' . $demande['nom']) ?></span></div></td>
                    <td><span class="type-badge <?= esc($typeClass) ?>"><?= esc($demande['libelle']) ?></span></td>
                    <td class="td-mono"><?= esc($demande['nb_jours']) ?> j</td>
                    <td><span class="statut s-<?= esc($demande['statut']) ?>"><?= esc(str_replace('_', ' ', $demande['statut'])) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3><i class="bi bi-person-slash" style="color:var(--muted);margin-right:5px"></i>Absents aujourd'hui</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.6rem">
              <?php if (empty($absents)): ?>
                <div class="td-muted" style="font-size:.8rem">Aucun absent aujourd'hui.</div>
              <?php else: ?>
                <?php foreach ($absents as $absent): ?>
                  <div style="display:flex;align-items:center;gap:8px">
                    <div class="avatar av-green" style="width:30px;height:30px;font-size:.65rem"><?= esc(strtoupper(substr($absent['prenom'], 0, 1) . substr($absent['nom'], 0, 1))) ?></div>
                    <div>
                      <div style="font-size:.83rem;font-weight:500;color:var(--ink)"><?= esc($absent['prenom'] . ' ' . $absent['nom']) ?></div>
                      <div style="font-size:.72rem;color:var(--muted)"><?= esc($absent['libelle']) ?> · retour <?= esc(date('d/m', strtotime($absent['date_fin']))) ?></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="flash flash-warn" style="margin:0">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span style="font-size:.8rem"><?= esc($criticalCount) ?> employé(s) ont un solde critique (≤ 2 jours).</span>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> <?= esc(date('Y')) ?> <span>TechMada RH</span></div>
  </div>
</div>
<?= $this->endSection() ?>
