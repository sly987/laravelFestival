<?php

$title = "/modificationAttributions";
include("_debut.inc.php");
include("_gestionBase.inc.php"); 
include("_controlesEtGestionErreurs.inc.php");
echo "<table width='80%' cellpadding='0' cellspacing='0' align='center'>
   <tr>
   <td align='center'><a href='index.php'>Accueil ></a><a href='consultationAttributions.php'>consultationAttributions ></a>modificationAttributions</td>
   </tr>
</table>
<br>";
// EFFECTUER OU MODIFIER LES ATTRIBUTIONS POUR L'ENSEMBLE DES ÉTABLISSEMENTS

// CETTE PAGE CONTIENT UN TABLEAU CONSTITUÉ DE 2 LIGNES D'EN-TÊTE (LIGNE TITRE ET 
// LIGNE ÉTABLISSEMENTS) ET DU DÉTAIL DES ATTRIBUTIONS 
// UNE LÉGENDE FIGURE SOUS LE TABLEAU

// Recherche du nombre d'établissements offrant des chambres pour le 
// dimensionnement des colonnes
$nbEtabOffrantChambres=obtenirNbEtabOffrantChambres($connexion);
$nb=$nbEtabOffrantChambres+4;
// Détermination du pourcentage de largeur des colonnes "établissements"
$pourcCol=50/$nbEtabOffrantChambres;

$action=$_REQUEST['action'];

// Si l'action est validerModifAttrib (cas où l'on vient de la page 
// donnerNbChambres.php) alors on effectue la mise à jour des attributions dans 
// la base 
if ($action=='validerModifAttrib')
{
   $idEtab=$_REQUEST['idEtab'];
   $idGroupe=$_REQUEST['idGroupe'];
   $nbChambres=$_REQUEST['nbChambres'];
   modifierAttribChamb($connexion, $idEtab, $idGroupe, $nbChambres);
}

echo "
<table width='80%' cellspacing='0' cellpadding='0' align='center' 
class='tabQuadrille'>";

   // AFFICHAGE DE LA 1ÈRE LIGNE D'EN-TÊTE
   echo "
   <tr class='enTeteTabQuad'>
      <td colspan=$nb><strong>Attributions</strong></td>
   </tr>";
      
   // AFFICHAGE DE LA 2ÈME LIGNE D'EN-TÊTE (ÉTABLISSEMENTS)
   echo "
   <tr class='enTeteTabQuadSecond'>
      <td>nom équipe</td>
      <td>pays d'origine</td>
      <td>nombre de chambres à avoir</td>
      <td>chambres attribués</td>";
      
   $lgEtab=obtenirReqEtablissementsOffrantChambres($connexion);

   // Boucle sur les établissements (pour afficher le nom de l'établissement et 
   // le nombre de chambres encore disponibles)
   foreach ($lgEtab as $row)
   {
      $idEtab=$row["idEtab"];
      $nom=$row["nomEtab"];
      $nbOffre=$row["nombreChambresOffertes"];
      $nbOccup=obtenirNbOccup($connexion, $idEtab);
                    
      // Calcul du nombre de chambres libres
      $nbChLib = $nbOffre - $nbOccup;
      if($nbChLib==0)
      {
         echo "
         <td valign='top' width='$pourcCol%'><i>Disponibilités : <strong>complet </strong></i> <br>
         $nom</td>";  
      }
      else
      {
         echo "
         <td valign='top' width='$pourcCol%'><i>Disponibilités : $nbChLib </i> <br>
         $nom </td>";
      }
   }
   echo "</tr>"; 

   // CORPS DU TABLEAU : CONSTITUTION D'UNE LIGNE PAR GROUPE À HÉBERGER AVEC LES 
   // CHAMBRES ATTRIBUÉES ET LES LISTES DEROULANTES POUR EFFECTUER OU MODIFIER LES ATTRIBUTIONS
         
   $req=obtenirReqIdNomGroupesAHeberger();
   $rsGroupe=$connexion-> query($req);
   $lgGroupe=$rsGroupe->fetchAll();
   
   
   // BOUCLE SUR LES GROUPES À HÉBERGER 

   foreach ($lgGroupe as $row)
   {
      $idGroupe=$row['idGroupe'];
      $nom=$row['nomGroupe'];
      $nomPays =$row['nomPays'];
      $nbChambres =  intdiv($row['nombrePersonnes'],3);
      if($row['nombrePersonnes']%3>0)
      {
         $nbChambres = $nbChambres + 1;
      }

      echo "
      <tr class='ligneTabQuad'>
         <td width='15%'>$nom</td>
         <td width='10%'>$nomPays</td>
         <td width='5%'>$nbChambres</td>";
//nbTotal récupère le nombre de chambres total attribué à un groupe
      $nbTotal=0;
      $lgEtab=obtenirReqEtablissementsOffrantChambres($connexion);

      foreach($lgEtab as $row)
      {
         $idEtab=$row['idEtab'];
         
         $nbOccupGroupe=obtenirNbOccupGroupe($connexion, $idEtab, $idGroupe);
         $nbTotal+=$nbOccupGroupe;
      }
      echo"<td width = '3%'>$nbTotal</td>";
      
      $lgEtab=obtenirReqEtablissementsOffrantChambres($connexion);
      // BOUCLE SUR LES ÉTABLISSEMENTS
      foreach ($lgEtab as $row)
      {

         $idEtab=$row["idEtab"];
         $nbOffre=$row["nombreChambresOffertes"];
         $nbOccup=obtenirNbOccup($connexion, $idEtab);
         echo"<form method='POST' action='modificationAttributions.php'>
         <input type='hidden' value='validerModifAttrib' name='action'>
         <input type='hidden' value='$idEtab' name='idEtab'>
         <input type='hidden' value='$idGroupe' name='idGroupe'>";          
         // Calcul du nombre de chambres libres
         $nbChLib = $nbOffre - $nbOccup;
                  
         // On recherche si des chambres ont déjà été attribuées à ce groupe
         // dans cet établissement
         $nbOccupGroupe=obtenirNbOccupGroupe($connexion, $idEtab, $idGroupe);
         
         // Cas où des chambres ont déjà été attribuées à ce groupe dans cet
         // établissement
         if ($nbOccupGroupe!=0)
         {
            // Le nombre de chambres maximum pouvant être demandées est la somme 
            // du nombre de chambres libres et du nombre de chambres actuellement 
            // attribuées au groupe (ce nombre $nbmax sera transmis si on 
            // choisit de modifier le nombre de chambres)
            if($nbChLib + $nbOccupGroupe < $nbOccupGroupe+$nbChambres-$nbTotal)
            {
               $nbMax = $nbChLib + $nbOccupGroupe;
            }
            else
            {
               $nbMax = $nbOccupGroupe+$nbChambres-$nbTotal;
            }


            echo "<td class='reserve'>
            &nbsp;<select name='nbChambres'>
            ";
            for ($i=0; $i<=$nbMax; $i++)
            {
               if($nbOccupGroupe==$i)
                  echo"<option selected>$i</option>";
               else
                  echo "<option>$i</option>";

            }
            echo "</select>
            <input type='submit' value='valider'>
            </form>
            </td>";
         
         }
         else
         {
            // Cas où il n'y a pas de chambres attribuées à ce groupe dans cet 
            // établissement : on affiche une liste déroulante s'il y a 
            // des chambres libres et s'il y a encore des chambres à reservées sinon "complet" est affiché    
            if ($nbChLib != 0 && $nbChambres-$nbTotal>0)
            {
               if($nbChLib<$nbChambres-$nbTotal)
               {
                  $nbMax = $nbChLib; 
               }
               else
               {
                  $nbMax = $nbChambres-$nbTotal;
                  
               }

               echo "<td>
               &nbsp;<select name='nbChambres'>
               <option selected>0</option>";
               for ($i=1; $i<=$nbMax; $i++)
               {
                  echo "<option>$i</option>";
                  
               }
               echo "</select>
               <input type='submit' value='valider'>
               </form>
               </td>";
            }
            else
            {
               echo "<td class='reserveSiLien'>complet</td>";
            }
         }  

      } // Fin de la boucle sur les établissements
       echo "</tr>";      
   } // Fin de la boucle sur les groupes à héberger
echo "
</table>"; // Fin du tableau principal

// AFFICHAGE DE LA LÉGENDE
echo "
<table align='center' width='80%'>
   <tr>
      <td width='34%' align='left'><a href='consultationAttributions.php'>Retour</a>
      </td>
      <td class='reserveSiLien'>&nbsp;</td>
      <td width='30%' align='left'>Complet</td>
      <td class='reserve'>&nbsp;</td>
      <td width='30%' align='left'>Chambres réservées</td>
   </tr>
</table>
</font>";
?>
