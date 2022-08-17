<?php

/* C'est en tout début de fichier que l'on vérifie les autorisations.
Les news sont visibles par tous, mais si vous voulez en restreindre l'accès,
c'est ici que cela se passe. */

 //On inclut le modèle
 include 'modeles/bdd.php';

 // On stock le nombre total des personnes enregistrees
$nombre=comptes(); 

if (isset($_GET['Deconnexion']))
{ 
    /* Pour se deconnecter on retire la valeur de $_SESSION['id'] */
   $_SESSION['id'] = NULL;
    session_destroy();
    header('Location: index.php');
}


if (isset($_GET['test']) AND $_GET['test']=='Accueil')
{
    if ($_SESSION['id']== NULL)
    {
        /* ICI l'utilisateur n'est pas connecté */
        include 'vues/test.php';
    }
    else 
    {
    /* Ici c'est au cas ou un utilisateur deja connect desir revenir sur sa page d'accueil */
    $_GET['connect'] =1;
    $moi= recuperer($_SESSION['id']);
    include 'vues/test.php';
    }
}

if (isset($_GET['test']) AND $_GET['test']=='Connexions')
 { 
    $id = Connexions('"'.$_POST['logine'].'"', '"'.$_POST['mot_de_passe'].'"');
    if (empty($id))
    {
       /* Le login et mot de passe ne trouvent pas dans la base des donnees */
        $_GET['pages'] ='erreur'; // La variable $_GET['pages']  est utilisee sur la page de connection
        include 'vues/connexion.php';
    }
     else
    {
        /* La variable  $_SESSION['id'] garde l'id de la personne connectees */
    $_SESSION['id'] = $id;
    $_GET['connect'] =1; // La variable $_GET['connect']  est utilisee sur la page d'accueil d'un utilisateur connecté ou non
    $moi= recuperer($_SESSION['id']);
    include 'vues/test.php';
    
    }
}

if (isset($_GET['test']) AND $_GET['test']=='Inscriptions')
{
    $tests = verification('"'.$_POST['logine'].'"');
    if ($tests==1)
    {
        enregistrer();
        comptes();
        $nombre=comptes(); 

        $_GET['pages'] ='succees';
        include 'vues/connexion.php';
    }

    else
    {
        /* Ici le login est deja utilise */
        $_GET['pagef'] = 'deja';
        include 'vues/inscription/inscription.php';
    }
    }

    if (isset($_GET['test']) AND $_GET['test']=='Suppression')
    {
        supprimer($_SESSION['id']);
        comptes();
        $nombre=comptes(); 
        $_SESSION['id']=NULL;
        $_GET['connect'] =0;
        $_GET['pages'] ='supp';
        include 'vues/connexion.php';
    }
 
    if (isset($_GET['test']) AND $_GET['test']=='Affichage')
    {
        
    if ($_SESSION['id']== NULL)
    {
        /* ICI l'utilisateur n'est pas connecté */
        $etudiants=afficher();
        include 'vues/testaff.php';
    }
    else
    {
         /* Ici c'est au cas ou un utilisateur deja connect desir revenir sur sa page d'accueil */
    $_GET['connect'] =1;
    $moi= recuperer($_SESSION['id']);
    $etudiants=afficher();
    include 'vues/testaff.php';
    }
}
if (isset($_GET['test']) AND $_GET['test']=='Seconnecter')
{
    include 'vues/connexion.php';
}

// Je pourrai peut etre en avoir besoin: include(dirname(__FILE__).'/../vues/.....');
?>