<?php
include("connexion-PDO.php");
$title = "/listeGroupe";
include("_debut.inc.php");
include("_gestionBase.inc.php"); 
include("_controlesEtGestionErreurs.inc.php");

echo "<table width='80%' cellpadding='0' cellspacing='0' align='center'>
   <tr>
   <td align='center'><a href='index.php'>Accueil ></a>listeGroupe
   </tr>
</table>
<br>";
echo '<table width = 40% align = "center">
	<form method ="POST" action = "résultatGroupe.php">
	<tr>
	<td width = 50% align = "right"><input type = "search" name = "searchGroupe"/></td>
	<td><input type = "submit" align = "left" name = "chercher" value ="chercher Groupe" /></td> </tr></table></form></br>';
echo "
<table width='70%' cellspacing='0' cellpadding='0' align='center' 
class='tabNonQuadrille'>
   <tr class='enTeteTabNonQuad'>
      <td colspan='4'>Groupe</td>
   </tr>";
     echo "
   <tr class='ligneTabNonQuad'>
      <td colspan='4'><a href='creationGroupe.php?action=demanderCreGr'>
      Création d'un groupe</a ></td>
  </tr>";
     
   $req=obtenirReqGroupe();
   $rsGroupe=$connexion->query($req);
   $lgGroupe=$rsGroupe->fetchAll();
   // BOUCLE SUR LES ÉTABLISSEMENTS
   foreach ($lgGroupe as $row)
   {
      $idGroupe=$row['idGroupe'];
      $nomGroupe=$row['nomGroupe'];
      $nbChambres =  intdiv($row['nombrePersonnes'],3);
      if($row['nombrePersonnes']%3>0)
      {
         $nbChambresDemande = $nbChambres + 1;
      }
      echo "
		<tr class='ligneTabNonQuad'>
         <td width='52%'>$nomGroupe</td>
         
         <td width='16%' align='center'> 
         <a href='detailGroupe.php?idGroupe=$idGroupe'>
         Voir détail</a></td>
         
         <td width='14%' align='center'> 
         <a href='modificationGroupe.php?action=demanderModifGroupe&amp;idGroupe=$idGroupe'>
         Modifier</a></td>";
      	
         // S'il existe déjà des attributions pour l'établissement, il faudra
         // d'abord les supprimer avant de pouvoir supprimer l'établissement
			if (!existeAttributionsGroupe($connexion, $idGroupe))
			{
            echo "
            <td width='18%' align='center'> 
            <a href='suppressionGroupe.php?action=demanderSupprGroupe&amp;idGroupe=$idGroupe'>
            Supprimer</a></td>";
        	 }
         	else
         	{
         		$nbChambre = obtenirNbGroupeOccup($connexion, $idGroupe);
            	echo "
            	<td width='18%'>&nbsp ".$nbChambre."/$nbChambresDemande chambres occupées </td>";          
			}
			echo "</tr>";
   }   
 	echo "</table>"


?>