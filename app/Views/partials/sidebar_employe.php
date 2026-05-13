<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
    <div class="sidebar-brand-name">TechMada RH<span>Espace employe</span></div>
  </div>
  <div class="sidebar-section">Menu</div>
  <ul class="sidebar-nav">
    <li><a href="<?= site_url('employe') ?>" class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
    <li><a href="<?= site_url('employe/conges/nouveau') ?>" class="<?= ($active ?? '') === 'nouveau' ? 'active' : '' ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
    <li>
      <a href="<?= site_url('employe/conges') ?>" class="<?= ($active ?? '') === 'mes-conges' ? 'active' : '' ?>">
        <i class="bi bi-calendar3"></i> Mes demandes
        <?php if (!empty($pendingCount)): ?><span class="nav-badge alert"><?= esc($pendingCount) ?></span><?php endif; ?>
      </a>
    </li>
    <li><a href="<?= site_url('employe/profil') ?>" class="<?= ($active ?? '') === 'profil' ? 'active' : '' ?>"><i class="bi bi-person"></i> Mon profil</a></li>
  </ul>
  <div class="sidebar-user">
    <div class="s-user-row">
      <div class="avatar av-green"><?= esc(substr(session('user_name') ?? 'U', 0, 2)) ?></div>
      <div>
        <div class="user-name"><?= esc(session('user_name') ?? '') ?></div>
        <div class="user-role">Employe</div>
      </div>
      <a href="<?= site_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Deconnexion"><i class="bi bi-box-arrow-right"></i></a>
    </div>
  </div>
</aside>
