<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<?php
$active = 'nouveau';
$dateDebut = old('date_debut') ?? '';
$dateFin = old('date_fin') ?? '';
$nbJours = 0;
if ($dateDebut !== '' && $dateFin !== '') {
    $nbJours = calculate_business_days($dateDebut, $dateFin);
}
?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_employe') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Nouvelle demande de congé</div>
        <div class="topbar-breadcrumb">
          <a href="<?= site_url('employe') ?>">Accueil</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande
        </div>
      </div>
    </div>

    <div class="content">
      <?= $this->include('partials/flash') ?>

      <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start" class="form-layout">
        <div>
          <form class="form-section" method="post" action="<?= site_url('employe/conges') ?>">
            <?= csrf_field() ?>
            <h3>Détails de la demande</h3>

            <div class="f-group">
              <label class="f-label">Type de congé <span style="color:var(--danger)">*</span></label>
              <select class="f-select" name="type_conge_id" required>
                <option value="">-- Choisir un type --</option>
                <?php foreach ($types as $type): ?>
                  <option value="<?= esc($type['id']) ?>" <?= old('type_conge_id') == $type['id'] ? 'selected' : '' ?>>
                    <?= esc($type['libelle']) ?> (<?= esc($type['restant']) ?> j restants<?= (int) $type['deductible'] === 0 ? ', non déductible' : '' ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-grid-2">
              <div class="f-group">
                <label class="f-label">Date de début <span style="color:var(--danger)">*</span></label>
                <input type="date" class="f-input" name="date_debut" value="<?= esc($dateDebut) ?>" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Date de fin <span style="color:var(--danger)">*</span></label>
                <input type="date" class="f-input" name="date_fin" value="<?= esc($dateFin) ?>" required/>
              </div>
            </div>

            <?php if ($nbJours > 0): ?>
              <div class="f-computed">
                <div class="f-computed-num"><?= esc($nbJours) ?></div>
                <div class="f-computed-label">jours ouvrables calculés</div>
              </div>
            <?php endif; ?>

            <div class="f-group" style="margin-bottom:1rem">
              <label class="f-label">Motif (optionnel)</label>
              <textarea class="f-textarea" name="motif" placeholder="Précisez le motif de votre demande si nécessaire..."><?= esc(old('motif') ?? '') ?></textarea>
              <div class="f-hint">Le motif est visible par le responsable RH.</div>
            </div>

            <div class="form-actions">
              <button class="btn-forest" type="submit"><i class="bi bi-send"></i> Soumettre la demande</button>
              <a href="<?= site_url('employe') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
            </div>
          </form>
        </div>

        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
              <?php foreach ($types as $type): ?>
                <?php
                  $attribues = max(1, (int) $type['jours_attribues']);
                  $restant = (int) $type['restant'];
                  $percent = (int) round(($restant / $attribues) * 100);
                  $fillClass = '';
                  if ($percent <= 30) {
                    $fillClass = ' warn';
                  }
                ?>
                <div>
                  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                    <span style="font-size:.8rem;color:var(--ink)"><?= esc($type['libelle']) ?></span>
                    <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500"><?= esc($restant) ?> j</span>
                  </div>
                  <div class="solde-bar"><div class="solde-fill<?= $fillClass ?>" style="width:<?= esc(max(0, min(100, $percent))) ?>%"></div></div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="flash flash-info" style="margin:0">
            <i class="bi bi-info-circle-fill"></i>
            <span style="font-size:.8rem">Le solde est déduit uniquement à l'approbation.</span>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> <?= esc(date('Y')) ?> <span>TechMada RH</span></div>
  </div>
</div>
<?= $this->endSection() ?>
