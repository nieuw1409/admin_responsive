<?php
/**
 * Cette class a pour but de minimiser les appels a la base de données 
 * en ce qui concerne la recherche du(des) père(s) d'une catégorie. (a l'origine)
 * 
 * Le principe est simple: récupérer l'ensemble des catégories ainsi que leurs pères
 * une fois pour toute puis les stocker pour simplifier et accélérer leurs appels.
 * 
 * @author Adrien Chantôme
 *
 */
class Categories
{
	// attribut pour le pattern Singleton.
	private static $moi	= null;
	private $tabIndex	= array();
	
	/**
	 * Le constructeur de la catégorie.
	 * C'est ici qu'est effectué la seule et unique requête.
	 * Je suis ici le design pattern singleton pour m'assurer que la requête ne sera effectuée qu'une et une seule fois.
	 * 
	 * (pour rappel, le Design pattern singleton est une façon de coder un objet 
	 * sur lequel on souhaite assurer l'unicité de la création. Pour cela,
	 * on rend le constructeur privé, on crée un "loader" qui ne crée l'objet
	 * que s'il n'existe pas encore.)
	 */
	private function Categories()
	{
		$temporaire	= array();
		$requete	= tep_db_query('SELECT DISTINCT parent_id, categories_id FROM '.TABLE_CATEGORIES.' ORDER BY categories_id');
		// Une fois la requête effectuée, on stocke temporairement les objets ainsi que l'id de leurs pères dans un tableau.
		while($temp	= tep_db_fetch_array($requete))
		{
			$temporaire[$temp['categories_id']] = array('obj'	=> new Categorie($temp['categories_id']),
														'pere'	=> $temp['parent_id']);
		}
		reset($temporaire);
		// Finalement, on parcours le tableau et on place les objets dans le tableau index, puis on affecte les fils aux pères concernés.
		foreach($temporaire as $clef => $t)
		{
			$this->tabIndex[$clef] = $t['obj'];
			if($t['pere'] != 0)
			{
				$temporaire[$t['pere']]['obj']->setFils($t['obj']);
			}
		}
	}
	
	/**
	 * Cette fonction est le "loader" du pattern singleton.
	 * Il vérifie si l'objet est déjà créé et si non, le crée une fois pour toute.
	 * 
	 * @return (Categories) la seule et unique Categories.
	 */
	public static function getCategories()
	{
		if(self::$moi === null)
		{
			self::$moi = new Categories();
		}
		return self::$moi;
	}
	
	/**
	 * Cette fonction retourne l'id du père de la catégorie concernée.
	 * @param $idCategorie : l'id de la catégorie concernée.
	 * @return (int) l'id du père.
	 */
	public function getPere($idCategorie)
	{
		$pere = $this->tabIndex[$idCategorie]->getPere();
		if($pere != null)
		{
			return $pere->getId();
		}
		else
		{
			return 0;
		}
	}
	
	// Prévient les utilisateurs sur le clônage de l'instance
    public function __clone()
    {
        trigger_error('Le clônage n\'est pas autorisé.', E_USER_ERROR);
    }
}