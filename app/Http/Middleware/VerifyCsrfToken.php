<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = ['http://127.0.0.1:8000/listeStand.php', 'http://127.0.0.1:8000/attributionStand.php', 'http://127.0.0.1:8000/modificationAttribution.php', 'http://127.0.0.1:8000/baseModifie.php', '_gestionBase.inc.php', 'http://127.0.0.1:8000/modificationAttributions.php?action=demanderModifAttrib', 'http://127.0.0.1:8000/creationGroupe.php?action=demanderCreGr', 'http://127.0.0.1:8000/creationEtablissement.php?action=demanderCreEtab', 'http://127.0.0.1:8000/detailEtablissement.php', 'http://127.0.0.1:8000/modificationEtablissement.php?action=demanderModifEtab', 'http://127.0.0.1:8000/modificationEtablissement.php', 'http://127.0.0.1:8000/suppressionEtablissement.php', 'http://127.0.0.1:8000/modificationAttributions.php'
        //
    ];
}
