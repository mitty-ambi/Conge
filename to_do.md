## to list rh:
### Page de liste des demandes en attente:
    .. Affichage : 
        * Statistiques des demandes (Tous, en attente, accepte, refuse)
        * Tableau des listes des demandes:
            . user
            . type de conge demande
            . periode
            . duree
            . solde dispo
            . statut
            . actions
        * Refus inevitable(quota de conges insuffisant)
    .. Services : 
        * (ok)GetAlldemandes(Demande)
        * (ok)GetDemandeByStatut(Demande)
        * (ok)GetDemandeByDepartement(Demande)
        * (ok)GetSoldeUserByType(Solde)
        * (ok)ModifierStatut(Demande)
        * (ok)ModifierSolde(Solde)
        * (ok)GetAgentDedecision(celui qui a approuve ou refuse la demande)(DemandeDecision)