<?php
include("connexion-PDO.php");
$title = "/suppressionGroupe";
include("_debut.inc.php");
include("_gestionBase.inc.php"); 
include("_controlesEtGestionErreurs.inc.php");
echo "<table width='80%' cellpadding='0' cellspacing='0' align='center'>
   <tr>
   <td align='center'><a href='index.php'>Accueil ></a><a href='listeGroupe.php'>listeGroupe ></a>suppressionGroupe
   </tr>
</table>
<br>";


// SUPPRIMER UN ÉTABLISSEMENT 

$idGroupe=$_REQUEST['idGroupe'];  

$lgGroupe=obtenirDetailGroupe($connexion, $idGroupe);
foreach($lgGroupe as $row)
$nomGroupe=$row['nomGroupe'];

// Cas 1ère étape (on vient de listeEtablissements.php)

if ($_REQUEST['action']=='demanderSupprGroupe')    
{
   echo "
   <br><center><h5>Souhaitez-vous vraiment supprimer le groupe $nomGroupe ? 
   <br><br>
   <a href='suppressionGroupe.php?action=validerSupprEtab&amp;idGroupe=$idGroupe'>
   Oui</a>&nbsp; &nbsp; &nbsp; &nbsp;
   <a href='listeGroupe.php?'>Non</a></h5></center>";
}

// Cas 2ème étape (on vient de suppressionEtablissement.php)

else
{
   supprimerGroupe($connexion, $idGroupe);
   echo "
   <br><br><center><h5>Le groupe $nomGroupe a été supprimé</h5>
   <a href='listeGroupe.php?'>Retour</a></center>";
}

?>
