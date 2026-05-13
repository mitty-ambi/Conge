<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<section>
  <div class="auth-page geo-bg">
    <div class="auth-split">
      <div class="auth-left">
        <div>
          <p class="auth-left-brand">TechMada RH<span>Gestion des conges</span></p>
          <p class="auth-left-text" style="margin-top:2rem">
            <strong>Bienvenue sur votre espace RH.</strong>
            Gere vos demandes de conges, consulte ton solde et suis l'etat des demandes en temps reel.
          </p>
        </div>
        <div class="auth-roles">
          <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.25);margin-bottom:4px">Comptes de demonstration</div>
          <button type="button" class="role-pill demo-account" data-email="admin@techmada.mg" data-password="admin123" style="width:100%;text-align:left;cursor:pointer">
            <i class="bi bi-shield-check"></i>
            <div><div class="role-pill-name">Administrateur</div><div class="role-pill-cred">admin@techmada.mg · admin123</div></div>
          </button>
          <button type="button" class="role-pill demo-account" data-email="rh@techmada.mg" data-password="rh123" style="width:100%;text-align:left;cursor:pointer">
            <i class="bi bi-person-check"></i>
            <div><div class="role-pill-name">Responsable RH</div><div class="role-pill-cred">rh@techmada.mg · rh123</div></div>
          </button>
          <button type="button" class="role-pill demo-account" data-email="employe@techmada.mg" data-password="emp123" style="width:100%;text-align:left;cursor:pointer">
            <i class="bi bi-person"></i>
            <div><div class="role-pill-name">Employe</div><div class="role-pill-cred">employe@techmada.mg · emp123</div></div>
          </button>
        </div>
      </div>

      <div class="auth-right">
        <p class="auth-title">Connexion</p>
        <p class="auth-sub">Entrez vos identifiants pour acceder a votre espace.</p>

        <?= $this->include('partials/flash') ?>

        <form method="post" action="<?= site_url('login') ?>">
          <?= csrf_field() ?>
          <div class="f-group">
            <label class="f-label">Adresse email</label>
            <input type="email" name="email" id="login-email" class="f-input" placeholder="vous@techmada.mg" value="<?= esc(old('email') ?? '') ?>"/>
          </div>
          <div class="f-group">
            <label class="f-label">Mot de passe</label>
            <input type="password" name="password" id="login-password" class="f-input" placeholder="••••••••"/>
          </div>
          <button type="submit" class="btn-primary" style="margin-top:.5rem">
            Se connecter <i class="bi bi-arrow-right-short"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>
<script>
document.querySelectorAll('.demo-account').forEach(function (button) {
  button.addEventListener('click', function () {
    var emailInput = document.getElementById('login-email');
    var passwordInput = document.getElementById('login-password');
    if (!emailInput || !passwordInput) {
      return;
    }

    emailInput.value = button.dataset.email || '';
    passwordInput.value = button.dataset.password || '';
    passwordInput.focus();
  });
});
</script>
<?= $this->endSection() ?>
