<?php
namespace Lang;

/**
 * @author   Florestan Bredow <florestan.bredow@supagro.fr>
 * @license  MIT
 */
class Lang
{

    private $chunks = array();
    private $defaultLanguage;

    /**
     * Constructeur
     */
    public function __construct($pageContent, $defaultLanguage = "en")
    {
        $this->chunks = $this->cut($pageContent);
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * renvois le contenu de la page dans la langue demandé, ou la langue par
     * défaut si la langue demandée n'est pas disponible.
     *
     * @param  string $lang code pays (fr, en, es, hu...)
     * @return [type]       [description]
     */
    public function get($lang)
    {
        // Si la langue demandée n'existe pas,
        // la langue par défaut est retournée
        if (!array_key_exists($lang, $this->chunks)) {
            $lang = $this->defaultLanguage;
            // Si la langue par défaut n'existe pas, la premiere langue
            // disponible est renvoyée.
            if (!array_key_exists($lang)) {
                reset($this->chunks);
                $lang = array_keys($this->chunks)[0];
            }
        }
        return $this->chunks[$lang];
    }

    public function getLangList()
    {
        return array_keys($this->chunks);
    }

    /**
     * Découpe le contenu dans un tableau type :
     *  array(
     *      "code_langue" => "texte,
     *      "code_langue" => "texte,
     *      ...
     *  )
     * @param  $string $pageContent contenu de la page a traiter.
     * @return [type]              [description]
     */
    private function cut($pageContent)
    {
        // Dans le cas ou aucune {{lang="XX"}} n'est spécifiée.
        if (!preg_match("/{{lang=\"[a-zA-Z][a-zA-Z]\"}}/", $pageContent)) {
            return array($this->defaultLanguage => $pageContent);
        }

        // Supprime tout avant la première occurence de l'action lang
        $pageContent = strstr($pageContent, "{{lang=\"");

        $chunkedContent =  preg_split(
            "/{{lang=\"([a-zA-Z][a-zA-Z])*\"}}/ms",
            $pageContent,
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );

        // Réorganise le tableau, les valeurs paires deviennent les clés et les
        // valeurs impaires la valeur.
        $result = array();
        foreach ($chunkedContent as $key => $value) {
            // Ignore les clés impaires
            if ($key % 2 != 0) {
                continue;
            }
            $result[$value] = trim($chunkedContent[$key + 1]);
        }
        return $result;
    }

}
