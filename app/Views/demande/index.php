<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Demandes a traiter</h1>
    <p>Acceuil>Demandes</p>
    <select name="StatutDemande" id="statutdemande" onchange="getdonneesstatut()">
        <option value="-1">Tous les statuts</option>
        <?php foreach($statuts as $statut): ?>
            <option value="<?= $statut['id_statut'] ?>"><?= $statut['libelle'] ?></option>
        <?php endforeach; ?>
    </select>
    <select name="DepartementDemande" id="departementdemande" onchange="getdonneesdepartement()">
        <option value="-1">Tous les departements</option>
        <?php foreach($departements as $departement): ?>
            <option value="<?= $departement['id_departement'] ?>"><?= $departement['libelle'] ?></option>
        <?php endforeach; ?>
    </select>
    <table border="1" id=tabledemandes>
        <tr>
            <th>Type de conge</th>
            <th>Date de debut</th>
            <th>Date de fin</th>
            <th>Duree</th>
            <th>Statut</th>
            <th>Decision</th>
            <th>Action</th>
        </tr>
        <?php foreach($demandes as $demande): ?>
        <tr>
            <td><?= $demande['id_type'] ?></td>
            <td><?= $demande['date_debut'] ?></td>
            <td><?= $demande['date_fin'] ?></td>
            <td><?= $demande['duree'] ?></td>
            <td><?= $demande['statut'] ?></td>
            <td><?= $demande['decision'] ?></td>
            <td>
                <?php if($demande['statut'] == 'En attente'): ?>
                    <form action="/demande/accepter/<?= $demande['id_demande'] ?>" method="post" style="display:inline;">
                        <button type="submit">Accepter</button>
                    </form>
                    <form action="/demande/refuser/<?= $demande['id_demande'] ?>" method="post" style="display:inline;">
                        <button type="submit">Refuser</button>
                    </form>
                <?php else: ?>
                    <p>Traite par <?= $demande['decision']['Nom']?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <script>
            function getdonneesstatut() {
                var xhr = new XMLHttpRequest();
                var statutId = document.getElementById('statutdemande').value;
                if (String(statutId) === '-1') {
                    statutId = 0;
                }

                xhr.open('GET', '/demande/statut/' + encodeURIComponent(statutId), true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var payload;
                        try {
                            payload = JSON.parse(xhr.responseText);
                        } catch (e) {
                            console.error('Reponse JSON invalide', e, xhr.responseText);
                            return;
                        }

                        var demandes = (payload && Array.isArray(payload.demandes)) ? payload.demandes : [];
                        var table = document.getElementById('tabledemandes');
                        if (!table) return;

                        while (table.rows.length > 1) {
                            table.deleteRow(1);
                        }

                        for (var i = 0; i < demandes.length; i++) {
                            var d = demandes[i] || {};
                            var row = table.insertRow(-1);

                            var cellType = row.insertCell(0);
                            var cellDebut = row.insertCell(1);
                            var cellFin = row.insertCell(2);
                            var cellDuree = row.insertCell(3);
                            var cellStatut = row.insertCell(4);
                            var cellDecision = row.insertCell(5);
                            var cellAction = row.insertCell(6);

                            cellType.textContent = d.id_type ?? '';
                            cellDebut.textContent = d.date_debut ?? '';
                            cellFin.textContent = d.date_fin ?? '';
                            cellDuree.textContent = d.duree ?? '';
                            cellStatut.textContent = d.statut ?? '';

                            var decisionNom = (d.decision && d.decision.Nom) ? d.decision.Nom : '';
                            cellDecision.textContent = decisionNom;

                            if ((d.statut ?? '') === 'En attente') {
                                var formAcc = document.createElement('form');
                                formAcc.action = '/demande/accepter/' + encodeURIComponent(d.id_demande ?? '');
                                formAcc.method = 'post';
                                formAcc.style.display = 'inline';

                                var btnAcc = document.createElement('button');
                                btnAcc.type = 'submit';
                                btnAcc.textContent = 'Accepter';
                                formAcc.appendChild(btnAcc);

                                var formRef = document.createElement('form');
                                formRef.action = '/demande/refuser/' + encodeURIComponent(d.id_demande ?? '');
                                formRef.method = 'post';
                                formRef.style.display = 'inline';

                                var btnRef = document.createElement('button');
                                btnRef.type = 'submit';
                                btnRef.textContent = 'Refuser';
                                formRef.appendChild(btnRef);

                                cellAction.appendChild(formAcc);
                                cellAction.appendChild(formRef);
                            } else {
                                var p = document.createElement('p');
                                p.textContent = decisionNom ? ('Traite par ' + decisionNom) : 'Traite';
                                cellAction.appendChild(p);
                            }
                        }
                    }
                };
                xhr.send();
            }
            function getdonneesdepartement() {
                var xhr = new XMLHttpRequest();
                var departementId = document.getElementbyId("departementdemande").value;
                if (String(statutId) === '-1') {
                    statutId = 0;
                }

                xhr.open('GET', '/demande/departement/' + encodeURIComponent(statutId), true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var payload;
                        try {
                            payload = JSON.parse(xhr.responseText);
                        } catch (e) {
                            console.error('Reponse JSON invalide', e, xhr.responseText);
                            return;
                        }

                        var demandes = (payload && Array.isArray(payload.demandes)) ? payload.demandes : [];
                        var table = document.getElementById('tabledemandes');
                        if (!table) return;

                        while (table.rows.length > 1) {
                            table.deleteRow(1);
                        }

                        for (var i = 0; i < demandes.length; i++) {
                            var d = demandes[i] || {};
                            var row = table.insertRow(-1);

                            var cellType = row.insertCell(0);
                            var cellDebut = row.insertCell(1);
                            var cellFin = row.insertCell(2);
                            var cellDuree = row.insertCell(3);
                            var cellStatut = row.insertCell(4);
                            var cellDecision = row.insertCell(5);
                            var cellAction = row.insertCell(6);

                            cellType.textContent = d.id_type ?? '';
                            cellDebut.textContent = d.date_debut ?? '';
                            cellFin.textContent = d.date_fin ?? '';
                            cellDuree.textContent = d.duree ?? '';
                            cellStatut.textContent = d.statut ?? '';

                            var decisionNom = (d.decision && d.decision.Nom) ? d.decision.Nom : '';
                            cellDecision.textContent = decisionNom;

                            if ((d.statut ?? '') === 'En attente') {
                                var formAcc = document.createElement('form');
                                formAcc.action = '/demande/accepter/' + encodeURIComponent(d.id_demande ?? '');
                                formAcc.method = 'post';
                                formAcc.style.display = 'inline';

                                var btnAcc = document.createElement('button');
                                btnAcc.type = 'submit';
                                btnAcc.textContent = 'Accepter';
                                formAcc.appendChild(btnAcc);

                                var formRef = document.createElement('form');
                                formRef.action = '/demande/refuser/' + encodeURIComponent(d.id_demande ?? '');
                                formRef.method = 'post';
                                formRef.style.display = 'inline';

                                var btnRef = document.createElement('button');
                                btnRef.type = 'submit';
                                btnRef.textContent = 'Refuser';
                                formRef.appendChild(btnRef);

                                cellAction.appendChild(formAcc);
                                cellAction.appendChild(formRef);
                            } else {
                                var p = document.createElement('p');
                                p.textContent = decisionNom ? ('Traite par ' + decisionNom) : 'Traite';
                                cellAction.appendChild(p);
                            }
                        }
                    }
                };
                xhr.send();
            }
        </script>
</body>
</html>