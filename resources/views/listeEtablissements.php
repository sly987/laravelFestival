<?php
include("connexion-PDO.php");
$title = "/listeEtablissement";
include("_debut.inc.php");
include("_gestionBase.inc.php"); 
include("_controlesEtGestionErreurs.inc.php");
echo "<table width='80%' cellpadding='0' cellspacing='0' align='center'>
   <tr>
   <td align='center'><a href='index.php'>Accueil ></a>listeEtablissement</td>
   </tr>
</table>
<br>";
// CONNEXION AU SERVEUR MYSQL PUIS SÉLECTION DE LA BASE DE DONNÉES festival

// AFFICHER L'ENSEMBLE DES ÉTABLISSEMENTS
// CETTE PAGE CONTIENT UN TABLEAU CONSTITUÉ D'1 LIGNE D'EN-TÊTE ET D'1 LIGNE PAR
// ÉTABLISSEMENT

echo "
<table width='70%' cellspacing='0' cellpadding='0' align='center' 
class='tabNonQuadrille'>
   <tr class='enTeteTabNonQuad'>
      <td colspan='4'>Etablissements</td>
   </tr>";
     
   $req=obtenirReqEtablissements();
   $rsEtab=$connexion->query($req);
   $lgEtab=$rsEtab->fetchAll();
   // BOUCLE SUR LES ÉTABLISSEMENTS
   foreach ($lgEtab as $row) 
   {
      $id=$row['idEtab'];
      $nom=$row['nomEtab'];
      $nombreChambresOffertes=$row['nombreChambresOffertes'];

      echo "
		<tr class='ligneTabNonQuad'>
         <td width='52%'>$nom</td>
         
         <td width='16%' align='center'> 
         <a href='detailEtablissement.php?idEtab=$id'>
         Voir détail</a></td>
         
         <td width='16%' align='center'> 
         <a href='modificationEtablissement.php?action=demanderModifEtab&amp;idEtab=$id'>
         Modifier</a></td>";
      	
         // S'il existe déjà des attributions pour l'établissement, il faudra
         // d'abord les supprimer avant de pouvoir supprimer l'établissement
			if (!existeAttributionsEtab($connexion, $id))
			{

            echo "
            <td width='16%' align='center'> 
            <a href='suppressionEtablissement.php?action=demanderSupprEtab&amp;idEtab=$id'>
            Supprimer</a></td>";
         }
         else
         {

            $nb = obtenirNbOccup($connexion, $id);
            $nbMax = nbChambresMax($connexion, $id);
            if($nb == $nbMax)
            {
               echo "<td width='16%'>complet avec  $nombreChambresOffertes chambres </td>";
            }
            else{
               echo "
               <td width='16%'> ".$nb."/$nombreChambresOffertes chambres occupés  </td>";
            }          
			}
			echo "
      </tr>";
   }   
   echo "
   <tr class='ligneTabNonQuad'>
      <td colspan='4'><a href='creationEtablissement.php?action=demanderCreEtab'>
      Création d'un établissement</a ></td>
  </tr>
</table>";

?>
