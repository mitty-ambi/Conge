-- Mise à jour des id_UE pour chaque matière
UPDATE
    Matiere
SET
    id_UE = 1
WHERE
    codeMatiere IN ('INF201', 'INF203', 'INF207');

UPDATE
    Matiere
SET
    id_UE = 2
WHERE
    codeMatiere IN ('INF202', 'INF208');

UPDATE
    Matiere
SET
    id_UE = 3
WHERE
    codeMatiere IN ('MTH201', 'ORG201');

UPDATE
    Matiere
SET
    id_UE = 4
WHERE
    codeMatiere IN ('INF204', 'INF205');

UPDATE
    Matiere
SET
    id_UE = 5
WHERE
    codeMatiere IN ('INF206', 'INF209');

UPDATE
    Matiere
SET
    id_UE = 6
WHERE
    codeMatiere IN ('INF210', 'INF211', 'INF212');

UPDATE
    Matiere
SET
    id_UE = 7
WHERE
    codeMatiere IN ('MTH202', 'MTH203', 'MTH204', 'MTH205', 'MTH206');