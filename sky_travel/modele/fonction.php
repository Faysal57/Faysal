<?php
function recupDept($bdd) {
    $req = "SELECT * FROM departement ORDER BY code_dept ASC";
    $reqRecupDept = $bdd->prepare($req);
    $reqRecupDept->execute();
    $listeDept = $reqRecupDept->fetchAll();

    return $listeDept;
}

function recupVillesDept($bdd, $idDep) {
    $req = "SELECT * FROM ville WHERE id_dept = :idDept ORDER BY nom_ville ASC";
    $reqRecupVilles = $bdd->prepare($req);
    $reqRecupVilles->execute([':idDept'=>$idDep]);
    $listeVilles = $reqRecupVilles->fetchAll();

    return $listeVilles;
}

function recupUser($bdd, $idUser) {
    $req = "SELECT nom, prenom , date_naiss, adresse.adresse, departement.code_dept, departement.id_dept, departement.nom_dept,ville.id_ville, ville.nom_ville, email FROM utilisateur
    INNER JOIN adresse ON utilisateur.id_utilisateur = adresse.id_utilisateur
    INNER JOIN ville ON  ville.id_ville=adresse.id_ville
    INNER JOIN departement ON departement.id_dept= ville.id_dept
    WHERE utilisateur.id_utilisateur = ?";
    $reqRecupUser = $bdd->prepare($req);
    $reqRecupUser->execute([$idUser]);
    $user = $reqRecupUser->fetch();

    return $user;
}

function recupArticle($bdd) {
    $req = "SELECT * FROM article";
    $reqRecup = $bdd->prepare($req);
    $reqRecup->execute();
    $listeArticle = $reqRecup->fetchAll();

    return $listeArticle;
}

function recupTotalPanier($bdd) {
    $req = "SELECT montant FROM panier";
    $reqRecup = $bdd->prepare($req);
    $reqRecup->execute();
    $totalPanier = $reqRecup->fetch();

    return $totalPanier;
}

function getImage($bdd,$id_article ) {
    try {
       $reqs_image = $bdd->prepare('SELECT * FROM images WHERE id_article =:id_arti');
       $reqs_image->execute([':id_arti'=>$id_article]);
       $image = $reqs_image->fetch();
       return $image;

    }  
    catch(PDOException $e) {
        echo($e->getMessage()) ;
        die() ;
    }
}

function createUser($bdd,$nom, $prenom, $dateNaiss, $email, $mdp, $role) {
    $reqInsertUser = $bdd->prepare('INSERT INTO utilisateur(nom, prenom,date_naiss,email,mdp,role) VALUES (?,?,?,?,?,?)');
    $reqInsertUser->execute([$nom,$prenom,$dateNaiss,$email,$mdp,$role]);

    return $bdd->lastInsertId();
}

function insertAdresse($bdd, $adresse, $idUser, $idVille, $cp){   
    $reqInsertAdress = $bdd->prepare('INSERT INTO adresse(adresse, id_utilisateur, id_ville, cp) VALUES (?,?,?,?)');
    $reqInsertAdress->execute([$adresse, $idUser, $idVille, $cp]);
}

function insertPanier($bdd, $id_article, $prix_unit, $qte_com){   
    $reqInsertAdress = $bdd->prepare('INSERT INTO ajouter (id_article, prix_unit, qte_com) VALUES (?,?,?)');
    $reqInsertAdress->execute([$id_article, $prix_unit, $qte_com]);
}


function userExiste($bdd, $email){
    $req = "SELECT * FROM utilisateur WHERE email = :mail";
    $reqUserExists = $bdd->prepare($req);
    $reqUserExists->execute([':mail' => $email]);
    $userExists = $reqUserExists->fetch();
 
    return $userExists;
}

function userInfo($bdd){
    $req = "SELECT * FROM utilisateur WHERE id_utilisateur =?";
    $reqUserInfo = $bdd->prepare($req);
    $reqUserInfo->execute([$_SESSION['idUser']]);
    $userInfo = $reqUserInfo->fetch();
 
    return $userInfo;
}

function recupPrixArticle($bdd, $idArticle){
    $req = "SELECT prix_article FROM article WHERE id_article = :idArticle";
    $reqPrixArticle = $bdd->prepare($req);
    $reqPrixArticle->execute([':idArticle' => $idArticle]);
    $prixArticle = $reqPrixArticle->fetch();
 
    return $prixArticle['prix_article'];
}
function prodExisteDansPanier($bdd, $idProd){
    $reqProdExisteDansPanier = $bdd->prepare('SELECT * FROM ajouter WHERE id_article = ? AND id_panier = ?');
    $reqProdExisteDansPanier->execute([$idProd, $_SESSION['idPanier']]);
    $prodExisteDansPanier = $reqProdExisteDansPanier->fetch();

    return $prodExisteDansPanier;
}

function insertPanierDate($bdd, $montantProd, $date, $idUser){
    try {
        $req = 'INSERT INTO panier(montant, date_creation, id_utilisateur) VALUES(?,?,?)';
        $tabValues = [$montantProd,$date, $idUser];

        if ($idUser == 0){
            $req = 'INSERT INTO panier(montant, date_creation) VALUES(?,?)';
            $tabValues = [$montantProd,$date];
        }

        $reqInsertPanier = $bdd->prepare($req);
        $reqInsertPanier->execute($tabValues);
    } catch(PDOException $e) {
        echo "Erreur lors de l'insertion dans la table panier: " . $e->getMessage();
    }
}


function recupQteComArticle($bdd, $idArticle) {
    $req = $bdd->prepare('SELECT qte_com FROM ajouter WHERE id_panier = ? AND id_article = ?');
    $req->execute([$_SESSION['idPanier'], $idArticle]);
    $res = $req->fetch();
    
    // Vérifie si la requête a retourné un résultat
    if($res) {
        return $res['qte_com'];
    } else {
        return 0;
    }
}

function recupArticlesPanier($bdd, $idPanier) {
    $req = "SELECT * FROM article a, panier p, ajouter aj WHERE a.id_article = aj.id_article AND p.id_panier = aj.id_panier AND p.id_panier = ?";
    $reqRecupArticlesPanier = $bdd->prepare($req);
    $reqRecupArticlesPanier->execute([$idPanier]);
    $listeArticlesPanier = $reqRecupArticlesPanier->fetchAll();

    return $listeArticlesPanier;
}

function reqUpdateQteCom($bdd, $qteCom, $idArticle){
    $reqUpdateQteCom = $bdd->prepare('UPDATE ajouter SET qte_com = qte_com + ? WHERE id_panier = ? AND id_article = ?');
    $reqUpdateQteCom->execute([$qteCom, $_SESSION['idPanier'], $idArticle]);
}

function reqUpdateMontantPanier($bdd, $montantArticle){
    $reqUpdateMontantPanier = $bdd->prepare('UPDATE panier SET montant = montant + ? WHERE id_panier = ?');
    $reqUpdateMontantPanier->execute([$montantArticle, $_SESSION['idPanier']]);
}

function reqInsertNouvelleLigneAjouter($bdd, $idArticle, $qteCom, $prixArticle) {
    $reqInsertPanier = $bdd->prepare('INSERT INTO ajouter(id_panier, id_article, qte_com, prix_unit) VALUES(?,?,?,?)');
    $reqInsertPanier->execute([$_SESSION['idPanier'], $idArticle, $qteCom, $prixArticle]);
}

function reqSupprimerLigneAjouter($bdd, $idArticle) {
    $req = $bdd->prepare('DELETE FROM ajouter WHERE id_panier = ? AND id_article = ?');
    $req->execute([$_SESSION['idPanier'], $idArticle]);
}

function supprProdPanier($bdd, $idArticle, $idPanier)
{
    // Supprime la ligne correspondante dans la table ajouter
    $reqSupprLigneAjouter = $bdd->prepare('DELETE FROM ajouter WHERE id_article = :id_article AND id_panier = :id_panier');
    $reqSupprLigneAjouter->execute(array(
        'id_article' => $idArticle,
        'id_panier' => $idPanier 
    ));
}

function recupNbArticlesPanier($bdd) {
    $req = $bdd->prepare('SELECT COUNT(*) as nbArticles FROM ajouter WHERE id_panier = ?');
    $req->execute([$_SESSION['idPanier']]);
    $res = $req->fetch();
    
    // Vérifie si la requête a retourné un résultat
    if($res) {
        return $res['nbArticles'];
    } else {
        return 0;
    }
}

function supprimerPanier($bdd){
    $req = $bdd->prepare('DELETE FROM panier WHERE id_panier = ?');
    $req->execute([$_SESSION['idPanier']]);
}

function  recupDetailProdPanier($bdd, $idArticle){
    $req = $bdd->prepare('SELECT * FROM ajouter WHERE id_panier = ? AND id_article = ?');
    $req->execute([$_SESSION['idPanier'], $idArticle]);
    $res = $req->fetch();

    return $res;
}

function reqUpdatQteProdPanier($bdd, $idProd, $newQte){
    $reqUpdateMontantPanier = $bdd->prepare('UPDATE ajouter SET qte_com = ? WHERE id_panier = ? AND id_article = ?');
    $reqUpdateMontantPanier->execute([$newQte, $_SESSION['idPanier'], $idProd]);
}

function recupMontantPanier($bdd){
    $req = $bdd->prepare('SELECT montant FROM panier WHERE id_panier = ?');
    $req->execute([$_SESSION['idPanier']]);
    $res = $req->fetch();

    return $res['montant'];
}

function modifierUtilisateur($bdd, $nom, $prenom, $dateNaiss, $email, $adresse, $ville) {
    $reqUpdateUser = $bdd->prepare('UPDATE utilisateur SET nom=?, prenom=?, date_naiss=?, email=? WHERE id_utilisateur=?');
    $reqUpdateUser->execute([$nom, $prenom, $dateNaiss, $email, $_SESSION['idUser']]);

    $reqUpdateAdresse = $bdd->prepare('UPDATE adresse SET adresse=?, id_ville=? WHERE id_utilisateur=?');
    $reqUpdateAdresse->execute([$adresse, $ville, $_SESSION['idUser']]);
}

function updateMdp($bdd, $mdp){
   
    $reqUpdateMdp = $bdd->prepare('UPDATE utilisateur SET mdp=? WHERE id_utilisateur=?');
    $reqUpdateMdp->execute([$mdp, $_SESSION['idUser']]);
}

function genererToken($bdd, $token, $heureExpiration) {

    $req = $bdd->prepare("INSERT INTO recup_password (token, token_expiration) VALUES (?, ?)");
    $req->execute(array($token, $heureExpiration));

}

function recupToken($bdd, $idUser, $token){
    $req = $bdd->prepare('SELECT * FROM recup_password WHERE id_utilisateur = ? AND token = ?');
    $req->execute([$idUser, $token]);
    $res = $req->fetch();

    return $res;
}

?>