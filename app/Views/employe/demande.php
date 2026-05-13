<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <title>Document</title>
</head>
<?= view("navbar") ?>
<?= view("sidebar") ?>

<body>
    <!-- SECTION NOUVELLE DEMANDE -->
    <section id="page-form-conge">
        <div class="app-wrap">

            <!-- SIDEBAR -->
            <!-- MAIN CONTENT -->
            <div class="main">
                <div class="topbar">
                    <div>
                        <div class="topbar-title">Nouvelle demande de congé</div>
                        <div class="topbar-breadcrumb">
                            <a href="#page-dashboard-employe">Accueil</a>
                            <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande
                        </div>
                    </div>
                </div>

                <div class="content">
                    <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start"
                        class="form-layout">

                        <!-- Formulaire principal -->
                        <form action="/employe/addDemande" method="POST">
                            <div>
                                <input type="hidden" name="id_user" value="<?= session()->get('id_user') ?>">
                                <div class="form-section">
                                    <h3>Détails de la demande</h3>

                                    <div class="f-group" style="margin-bottom:1rem">
                                        <label class="f-label">Type de congé <span
                                                style="color:var(--danger)">*</span></label>
                                        <select class="f-select" name="id_type">
                                            <option value="">-- Choisir un type --</option>
                                            <?php foreach ($liste_type as $type): ?>
                                                <option value="<?= $type['id_type'] ?>">
                                                    <?= $type['nom'] ?> (<?= $type['solde_restant'] ?? 0 ?> j restants)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="f-error"><i class="bi bi-exclamation-circle"></i> Ce champ est
                                            requis.</div>
                                    </div>

                                    <div class="form-grid-2" style="margin-bottom:1rem">
                                        <div class="f-group">
                                            <label class="f-label">Date de début <span
                                                    style="color:var(--danger)">*</span></label>
                                            <input type="date" name="date_debut" class="f-input"
                                                value="<?= date('Y-m-d') ?>" />
                                        </div>
                                        <div class="f-group">
                                            <label class="f-label">Date de fin <span
                                                    style="color:var(--danger)">*</span></label>
                                            <input type="date" name="date_fin" class="f-input"
                                                value="<?= date('Y-m-d', strtotime('+5 days')) ?>" />
                                        </div>
                                    </div>

                                    <div class="f-group" style="margin-bottom:1rem">
                                        <label class="f-label">Motif (optionnel)</label>
                                        <textarea class="f-textarea" name="motif"
                                            placeholder="Précisez le motif de votre demande si nécessaire..."></textarea>
                                        <div class="f-hint">Le motif est visible par le responsable RH.</div>
                                    </div>

                                    <div class="form-actions">
                                        <button class="btn-forest" type="submit"><i class="bi bi-send"></i> Soumettre la
                                            demande</button>
                                        <a href="/employe/dashboard" class="btn-secondary"><i class="bi bi-x"></i>
                                            Annuler</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Panneau latéral : solde & règles -->
                        <div style="display:flex;flex-direction:column;gap:1rem">
                            <div class="data-card" style="margin:0">
                                <div class="data-card-head">
                                    <h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos
                                        soldes actuels</h3>
                                </div>
                                <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
                                    <div>
                                        <div
                                            style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                            <span style="font-size:.8rem;color:var(--ink)">Congé annuel</span>
                                            <span
                                                style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500">18
                                                j</span>
                                        </div>
                                        <div class="solde-bar">
                                            <div class="solde-fill" style="width:60%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                            <span style="font-size:.8rem;color:var(--ink)">Maladie</span>
                                            <span
                                                style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500">8
                                                j</span>
                                        </div>
                                        <div class="solde-bar">
                                            <div class="solde-fill" style="width:80%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                            <span style="font-size:.8rem;color:var(--ink)">Spécial</span>
                                            <span
                                                style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--warn);font-weight:500">1
                                                j</span>
                                        </div>
                                        <div class="solde-bar">
                                            <div class="solde-fill warn" style="width:20%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flash flash-info" style="margin:0">
                                <i class="bi bi-info-circle-fill"></i>
                                <span style="font-size:.8rem">Le solde est déduit uniquement à l'approbation de votre
                                    responsable.</span>
                            </div>

                            <div
                                style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:.85rem 1rem">
                                <div style="font-size:.78rem;font-weight:500;color:var(--ink);margin-bottom:.5rem">
                                    <i class="bi bi-clipboard-check"
                                        style="color:var(--forest);margin-right:5px"></i>Rappel
                                    des règles
                                </div>
                                <ul
                                    style="margin:0;padding-left:1rem;font-size:.75rem;color:var(--muted);line-height:1.7">
                                    <li>Préavis minimum : 48h avant la date de début</li>
                                    <li>Pas de chevauchement avec une demande en cours</li>
                                    <li>Solde insuffisant = demande refusée automatiquement</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="footer-app">
                    <i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span>
                </div>
            </div>

        </div>
    </section>

</body>

</html>