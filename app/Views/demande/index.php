<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/responsable.css">
</head>
<body>
    <div class="app-wrap">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-logo-icon">RH</div>
                <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
            </div>
            <div class="sidebar-section">Menu</div>
            <ul class="sidebar-nav">
                <li><a href="/demande" class="active">Demandes à traiter</a></li>
            </ul>
            <div class="sidebar-user">
                <div class="s-user-row">
                    <div class="avatar av-blue">RH</div>
                    <div>
                        <div class="user-name">Responsable RH</div>
                        <div class="user-role">Espace responsable</div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="main">
            <div class="topbar">
                <div>
                    <div class="topbar-title">Demandes à traiter</div>
                    <div class="topbar-breadcrumb"><a href="/demande">Accueil</a> &gt; Demandes</div>
                </div>
            </div>

            <div class="content">
                <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                    <select class="f-select" name="StatutDemande" id="statutdemande" onchange="getdonneesstatut()">
                        <option value="-1">Tous les statuts</option>
                        <?php foreach($statuts as $statut): ?>
                            <option value="<?= $statut['id_statut'] ?>"><?= $statut['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select class="f-select" name="DepartementDemande" id="departementdemande" onchange="getdonneesdepartement()">
                        <option value="-1">Tous les departements</option>
                        <?php foreach($departements as $departement): ?>
                            <option value="<?= $departement['id_departement'] ?>"><?= $departement['libelle'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="data-card">
                    <div class="data-card-head"><h3>Liste des demandes</h3></div>
                    <table class="tbl" id="tabledemandes">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Type</th>
                                <th>Période</th>
                                <th>Durée</th>
                                <th>Solde dispo</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($demandes as $demande): ?>
                            <?php
                                $statutLib = $demande['statut'] ?? '';
                                $statutClass = '';
                                if ($statutLib === 'En attente') {
                                    $statutClass = 's-attente';
                                } elseif ($statutLib === 'Accepté' || $statutLib === 'Approuvé' || $statutLib === 'Approuvée') {
                                    $statutClass = 's-approuvee';
                                } elseif ($statutLib === 'Refusé' || $statutLib === 'Refusée') {
                                    $statutClass = 's-refusee';
                                }
                                $decisionNom = is_array($demande['decision'] ?? null) ? ($demande['decision']['Nom'] ?? '') : '';
                                $employeNom = $demande['employe_nom'] ?? '';
                                $departementNom = $demande['departement_nom'] ?? '';
                                $typeLibelle = $demande['type_libelle'] ?? ($demande['id_type'] ?? '');
                                $typeClass = $demande['type_class'] ?? '';
                                $periodeAff = $demande['periode'] ?? (($demande['date_debut'] ?? '') . ' – ' . ($demande['date_fin'] ?? ''));
                                $soldeDispo = $demande['solde_dispo'] ?? null;
                            ?>
                            <tr>
                                <td>
                                    <div class="profile-row">
                                        <div class="avatar av-blue" style="width:32px;height:32px;font-size:.7rem"><?= htmlspecialchars($demande['employe_initials'] ?? 'U') ?></div>
                                        <div class="profile-info">
                                            <div class="pname"><?= $employeNom ?></div>
                                            <div class="pdept"><?= $departementNom ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="type-badge <?= $typeClass ?>"><?= $typeLibelle ?></span></td>
                                <td class="td-muted" style="font-size:.8rem"><?= $periodeAff ?></td>
                                <td class="td-mono"><?= ($demande['duree'] ?? '') ?> j</td>
                                <td>
                                    <?php if ($soldeDispo === null || $soldeDispo === ''): ?>
                                        <span class="td-muted">—</span>
                                    <?php else: ?>
                                        <span class="td-mono" style="color:var(--success);font-weight:650"><?= $soldeDispo ?> j</span>
                                        <span class="td-muted" style="font-size:.72rem"> dispo</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="statut <?= $statutClass ?>"><?= $statutLib ?></span></td>
                                <td>
                                    <?php if($statutLib == 'En attente'): ?>
                                        <div class="action-btns">
                                            <?php if($demande['duree'] < $soldeDispo) {?>
                                            <form action="/demande/accepter/<?= $demande['id_demande'] ?>" method="post" style="display:inline;">
                                                <button class="btn-sm btn-approve" type="submit">Accepter</button>
                                            </form>
                                            <?php }?>
                                            <form action="/demande/refuser/<?= $demande['id_demande'] ?>" method="post" style="display:inline;">
                                                <button class="btn-sm btn-refuse" type="submit">Refuser</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <p class="td-muted" style="margin:0">Traité</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function valOrEmpty(v) {
            return (v === null || v === undefined) ? '' : String(v);
        }

        function renderDemandes(demandes) {
            var table = document.getElementById('tabledemandes');
            if (!table) return;
            var tbody = (table.tBodies && table.tBodies[0]) ? table.tBodies[0] : table;
            tbody.innerHTML = '';

            for (var i = 0; i < demandes.length; i++) {
                var d = demandes[i] || {};
                var row = document.createElement('tr');

                var cellEmploye = document.createElement('td');
                var cellType = document.createElement('td');
                var cellPeriode = document.createElement('td');
                var cellDuree = document.createElement('td');
                var cellSolde = document.createElement('td');
                var cellStatut = document.createElement('td');
                var cellAction = document.createElement('td');

                // Employé
                var profileRow = document.createElement('div');
                profileRow.className = 'profile-row';
                var avatar = document.createElement('div');
                avatar.className = 'avatar av-blue';
                avatar.style.width = '32px';
                avatar.style.height = '32px';
                avatar.style.fontSize = '.7rem';
                avatar.textContent = valOrEmpty(d.employe_initials || 'U');
                var profileInfo = document.createElement('div');
                profileInfo.className = 'profile-info';
                var pname = document.createElement('div');
                pname.className = 'pname';
                pname.textContent = valOrEmpty(d.employe_nom);
                var pdept = document.createElement('div');
                pdept.className = 'pdept';
                pdept.textContent = valOrEmpty(d.departement_nom);
                profileInfo.appendChild(pname);
                profileInfo.appendChild(pdept);
                profileRow.appendChild(avatar);
                profileRow.appendChild(profileInfo);
                cellEmploye.appendChild(profileRow);

                // Type
                var typeSpan = document.createElement('span');
                typeSpan.className = 'type-badge ' + valOrEmpty(d.type_class);
                typeSpan.textContent = valOrEmpty(d.type_libelle || d.id_type);
                cellType.appendChild(typeSpan);

                // Période
                cellPeriode.className = 'td-muted';
                cellPeriode.style.fontSize = '.8rem';
                cellPeriode.textContent = valOrEmpty(d.periode || (valOrEmpty(d.date_debut) + ' – ' + valOrEmpty(d.date_fin)));

                // Durée
                cellDuree.className = 'td-mono';
                cellDuree.textContent = valOrEmpty(d.duree) ? (valOrEmpty(d.duree) + ' j') : '';

                // Solde dispo
                if (d.solde_dispo === null || d.solde_dispo === undefined || d.solde_dispo === '') {
                    var soldeMuted = document.createElement('span');
                    soldeMuted.className = 'td-muted';
                    soldeMuted.textContent = '—';
                    cellSolde.appendChild(soldeMuted);
                } else {
                    var soldeVal = document.createElement('span');
                    soldeVal.className = 'td-mono';
                    soldeVal.style.color = 'var(--success)';
                    soldeVal.style.fontWeight = '650';
                    soldeVal.textContent = valOrEmpty(d.solde_dispo) + ' j';
                    var soldeTxt = document.createElement('span');
                    soldeTxt.className = 'td-muted';
                    soldeTxt.style.fontSize = '.72rem';
                    soldeTxt.textContent = ' dispo';
                    cellSolde.appendChild(soldeVal);
                    cellSolde.appendChild(soldeTxt);
                }

                // Statut
                var statutText = valOrEmpty(d.statut);
                var statutSpan = document.createElement('span');
                var statutClass = '';
                if (statutText === 'En attente') {
                    statutClass = 's-attente';
                } else if (statutText === 'Accepté' || statutText === 'Approuvé' || statutText === 'Approuvée') {
                    statutClass = 's-approuvee';
                } else if (statutText === 'Refusé' || statutText === 'Refusée') {
                    statutClass = 's-refusee';
                }
                statutSpan.className = 'statut ' + statutClass;
                statutSpan.textContent = statutText;
                cellStatut.appendChild(statutSpan);

                // Actions
                var decisionNom = (d.decision && d.decision.Nom) ? d.decision.Nom : '';
                if (statutText === 'En attente') {
                    var actionWrap = document.createElement('div');
                    actionWrap.className = 'action-btns';

                    var formAcc = document.createElement('form');
                    formAcc.action = '/demande/accepter/' + encodeURIComponent(valOrEmpty(d.id_demande));
                    formAcc.method = 'post';
                    formAcc.style.display = 'inline';

                    var btnAcc = document.createElement('button');
                    btnAcc.type = 'submit';
                    btnAcc.textContent = 'Accepter';
                    btnAcc.className = 'btn-sm btn-approve';
                    formAcc.appendChild(btnAcc);

                    var formRef = document.createElement('form');
                    formRef.action = '/demande/refuser/' + encodeURIComponent(valOrEmpty(d.id_demande));
                    formRef.method = 'post';
                    formRef.style.display = 'inline';

                    var btnRef = document.createElement('button');
                    btnRef.type = 'submit';
                    btnRef.textContent = 'Refuser';
                    btnRef.className = 'btn-sm btn-refuse';
                    formRef.appendChild(btnRef);

                    actionWrap.appendChild(formAcc);
                    actionWrap.appendChild(formRef);
                    cellAction.appendChild(actionWrap);
                } else {
                    var p = document.createElement('p');
                    p.textContent = decisionNom ? ('Traité par ' + decisionNom) : 'Traité';
                    p.className = 'td-muted';
                    p.style.margin = '0';
                    cellAction.appendChild(p);
                }

                row.appendChild(cellEmploye);
                row.appendChild(cellType);
                row.appendChild(cellPeriode);
                row.appendChild(cellDuree);
                row.appendChild(cellSolde);
                row.appendChild(cellStatut);
                row.appendChild(cellAction);
                tbody.appendChild(row);
            }
        }

        function requestAndRender(url) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status !== 200) {
                    console.error('Erreur AJAX', xhr.status, xhr.responseText);
                    return;
                }
                var payload;
                try {
                    payload = JSON.parse(xhr.responseText);
                } catch (e) {
                    console.error('Réponse JSON invalide', e, xhr.responseText);
                    return;
                }
                var demandes = (payload && Array.isArray(payload.demandes)) ? payload.demandes : [];
                renderDemandes(demandes);
            };
            xhr.onerror = function() {
                console.error('Erreur réseau AJAX');
            };
            xhr.send();
        }

        function getdonneesstatut() {
            var statutId = document.getElementById('statutdemande').value;
            if (String(statutId) === '-1') {
                statutId = 0;
            }
            requestAndRender('/demande/statut/' + encodeURIComponent(statutId));
        }

        function getdonneesdepartement() {
            var departementId = document.getElementById('departementdemande').value;
            if (String(departementId) === '-1') {
                departementId = 0;
            }
            requestAndRender('/demande/departement/' + encodeURIComponent(departementId));
        }
    </script>
</body>
</html>