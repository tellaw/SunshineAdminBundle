<?php

namespace Tellaw\SunshineAdminBundle\Service;

use SplFileInfo;

/**
 * Utilitaires
 */
class UtilsService
{
    /**
     * Supprime les caractères spéciaux du nom d'un fichier
     *
     * @param $filename
     * @return string
     */
    public function cleanFileName($filename)
    {
        $info = new SplFileInfo($filename);
        $extension = $info->getExtension();

        if ($extension === '') {
            return $this->slugify($filename);
        } else {
            $basename = substr($filename, 0, strpos($filename, '.' . $extension));
            return $this->slugify($basename) . '.' . $extension;
        }
    }

    /**
     * Permet de retirer tous les caractères spéciaux d'une chaîne
     *
     * @param  string $slug Chaine à traiter
     * @return string       Même chaine sans les caractères spéciaux
     *
     * @author André Tapia <atapia@webnet.fr>
     */
    public static function slugify($slug)
    {
        $output = '';

        if (!empty($slug)) {
            // Etape 1 - on remplace toutes les lettres
            $accents = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
            $ssaccents = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
            $slug_1 = strtolower(strtr(utf8_decode(trim($slug)), utf8_decode($accents), $ssaccents));

            // Etape 2 - on remplace tous les charactères spéciaux
            $in = array(' ', '?', '!', '.', ',', ':', "'", '&', '(', ')', '[', ']');
            $out = array('-', '', '', '', '', '', '-', 'et', '', '', '', '');
            $slug_2 = str_replace($in, $out, $slug_1);

            // Etape 3 -
            $output = preg_replace('/([^.a-z0-9]+)/i', '-', $slug_2);

            // On supprime le dernier caractère si la chaine fini par '-'
            if (substr($output, -1) == '-') {
                $output = substr($output, 0, strlen($output) - 1);
            }
        }

        return $output;
    }
}
