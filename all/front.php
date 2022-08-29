<?php

session_start();



require_once("function.php");

// On établi la connexion à la base
$connexion=connexion();


// calcul du menu menu_haut====================================================
$requete="SELECT * FROM rubriques ORDER BY id_rubrique";
// on exécute la requête
$resultat=mysqli_query($connexion,$requete);

$menu_haut="<ul>";

while($ligne=mysqli_fetch_object($resultat)){
    if(!empty($ligne->lien_rubrique)){
        // lien externe au site
        $menu_haut.="<li><a href=\"".$ligne->lien_rubrique."\">".$ligne->nom_rubrique."</a></li>";
    } else {
        // sinon un lien interne
        $menu_haut.="<li><a href=\"front.php?p=".strtolower($ligne->nom_rubrique)."#".strtolower($ligne->nom_rubrique)."\">".$ligne->nom_rubrique."</a></li>";  
    }
    
}
$menu_haut.="</ul>";


//============================================================================
// calcul des pages à afficher
if(isset($_GET['p'])){
    //qq a cliqué sur un item
    $contenu=$_GET['p']. ".html";
}


// ============================================================================
// Gestion du formulaire de contact
// si qq appuie sur le bouton ENVOYER du formulaire

if(isset($_POST['submit'])){
    $message=array();
    if(empty($_POST['nom'])){
        $message['nom']="<label class=\"pas_ok\">Mets ton nom</label>";
        $color['nom']="class=\"color_champ\"";
    }
    elseif(empty($_POST['prenom'])){
        $message['prenom']="<label class=\"pas_ok\">Mets ton prénom</label>";
        $color['prenom']="class=\"color_champ\"";
    }
    elseif(empty($_POST['mel'])){
        $message['mel']="<label class=\"pas_ok\">Mets ton email</label>";
        $color['mel']="class=\"color_champ\"";
    }
    else {
        // On va stocker le contenu du formulaire dans le table contacts
        $requete="INSERT INTO contact SET 
        nom_contact='".security($_POST['nom'])."',         
        prenom_contact='".security($_POST['prenom'])."', 
        email_contact='".security($_POST['mel'])."',
        texte_contact='".security($_POST['message'])."',
        date_contact='".date("Y-m-d H:i:s")."' ";
        // on exécute la requête
        $resultat=mysqli_query($connexion,$requete);

        $visible="class=\"hidden\"";
        $merci="<h2 class=\"ok\">Envoyer !</h2>";
    }
}


mysqli_close($connexion);

include("front.html");


?>


