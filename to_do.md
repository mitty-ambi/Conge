# To-do par rôle (basé sur le code & routes existantes)

## RH — responsable rh
### Page **/demande** (DemandeController::index)
- **Important** : cette page “demande/index” est dédiée au **responsable RH** (traitement des demandes).
- **Règle d’accès** (côté logique applicative) :
  - si l’utilisateur connecté a `role = 'responsable rh'`, alors redirection/accès vers `/demande`.
  - sinon : rediriger vers le dashboard de son rôle (`/employe/dashboard` par défaut ; `/admin/dashboard` pour `admin`).
- **Routes associées** (demandes RH) :
  - `GET /demande` → liste globale
  - `GET /demande/statut/(:num)` → filtrage par statut (AJAX support)
  - `GET /demande/departement/(:num)` → filtrage par département (AJAX support)
  - `POST /demande/accepter/(:num)` → accepter
  - `POST /demande/refuser/(:num)` → refuser

### Affichage (app/Views/demande/index.php)
- Statut de chaque demande (badge) :
  - “En attente”
  - “Accepté/Approuvé/Approuvée”
  - “Refusé/Refusée”
- Tableau des demandes avec colonnes :
  - Employé (avatar + nom + département)
  - Type (badge + class type_class)
  - Période (date_debut – date_fin)
  - Durée (jours)
  - Solde dispo
  - Statut
  - Actions

### Actions RH
- Si **statut = En attente** :
  - bouton **Accepter** (affiché seulement si `duree < solde_dispo`)
  - bouton **Refuser** (toujours disponible)
- Sinon : texte “Traitée” (et éventuellement “Traitée par …”)

### Services / méthodes côté contrôleur (app/Controllers/DemandeController.php)
- `GetAlldemandes(Demande)` *(actuellement : DemandeModel->getAllDemandes)*
- `GetDemandeByStatut(Demande)` *(actuellement : getDemandeByStatut)*
- `GetDemandeByDepartement(Demande)` *(actuellement : getDemandeByDepartement)*
- `GetSoldeUserByType(Solde)` *(actuellement : SoldeModel->getSoldeByUserIdByType)*
- `ModifierStatut(Demande)` *(actuellement : demadeModel->modifyStatut)*
- `ModifierSolde(Solde)` *(actuellement : soldeModel->updateSolde)*
- `GetAgentDedecision(DemandeDecision)` *(actuellement : enrichDemandes utilise getDecisionByDemandeId, mais pas encore documentée comme “agent” complet dans la vue)*

### Logique métier
- Le contrôleur accepte :
  - met à jour le statut en `2`
  - décrémente le solde de l’employé (si id_user/id_type valides et durée > 0)
- Le contrôleur refuse :
  - met à jour le statut en `3`

## Employé
### Page /employe/dashboard (UtilisateurController::employe_dashboard)
- Affiche des statistiques pour l’employé :
  - en attente / approuvées / refusées
- Affiche un aperçu : `demandes` (slice(0,3))

### Page /employe/addDemande (DemandeCongeController)
- **Routes existantes** :
  - `GET /employe/addDemande` → go_to_crud
  - `POST /employe/addDemande` → add_demande
- À compléter dans `to_do.md` quand la vue CRUD est confirmée (pour l’instant : routes déjà câblées)

## Admin — pour l’instant
### /admin/dashboard (UtilisateurController::admin_dashboard)
- Pour l’instant : page de dashboard uniquement (aucune fonctionnalité métier listée dans le code actuel)

