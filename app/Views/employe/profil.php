<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<?php $active = 'profil'; ?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_employe') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Mon profil</div>
        <div class="topbar-breadcrumb">
          <a href="<?= site_url('employe') ?>">Accueil</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Profil
        </div>
      </div>
    </div>

    <div class="content">
      <?= $this->include('partials/flash') ?>

      <div style="max-width:760px">
        <div class="form-section">
          <h3><i class="bi bi-person" style="color:var(--forest);margin-right:6px"></i>Informations personnelles</h3>
          <form method="post" action="<?= site_url('employe/profil') ?>">
            <?= csrf_field() ?>
            <div class="form-grid-2">
              <div class="f-group">
                <label class="f-label">Prénom</label>
                <input type="text" class="f-input" name="prenom" value="<?= esc(old('prenom', $user['prenom'] ?? '')) ?>" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Nom</label>
                <input type="text" class="f-input" name="nom" value="<?= esc(old('nom', $user['nom'] ?? '')) ?>" required/>
              </div>
              <div class="f-group">
                <label class="f-label">Email</label>
                <input type="email" class="f-input" value="<?= esc($user['email'] ?? '') ?>" disabled/>
              </div>
            </div>

            <h3 style="margin-top:1.5rem"><i class="bi bi-shield-lock" style="color:var(--forest);margin-right:6px"></i>Sécurité</h3>
            <div class="form-grid-2">
              <div class="f-group">
                <label class="f-label">Nouveau mot de passe</label>
                <input type="password" class="f-input" name="password" placeholder="Laisser vide pour ne pas changer"/>
              </div>
              <div class="f-group">
                <label class="f-label">Confirmation</label>
                <input type="password" class="f-input" name="password_confirm" placeholder="Confirmer le mot de passe"/>
              </div>
            </div>

            <div class="form-actions">
              <button class="btn-forest" type="submit"><i class="bi bi-check2"></i> Enregistrer</button>
              <a href="<?= site_url('employe') ?>" class="btn-secondary">Retour</a>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> <?= esc(date('Y')) ?> <span>TechMada RH</span></div>
  </div>
</div>
<?= $this->endSection() ?>
