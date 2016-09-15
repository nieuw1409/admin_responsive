<?php
/**
 * Cette class a pour but de minimiser les appels a la base de donn�es 
 * en ce qui concerne la recherche du(des) p�re(s) d'une cat�gorie. (a l'origine)
 * 
 * Le principe est simple: r�cup�rer l'ensemble des cat�gories ainsi que leurs p�res
 * une fois pour toute puis les stocker pour simplifier et acc�l�rer leurs appels.
 * 
 * @author Adrien Chant�me
 *
 */
class Categories
{
	// attribut pour le pattern Singleton.
	private static $moi	= null;
	private $tabIndex	= array();
	
	/**
	 * Le constructeur de la cat�gorie.
	 * C'est ici qu'est effectu� la seule et unique requ�te.
	 * Je suis ici le design pattern singleton pour m'assurer que la requ�te ne sera effectu�e qu'une et une seule fois.
	 * 
	 * (pour rappel, le Design pattern singleton est une fa�on de coder un objet 
	 * sur lequel on souhaite assurer l'unicit� de la cr�ation. Pour cela,
	 * on rend le constructeur priv�, on cr�e un "loader" qui ne cr�e l'objet
	 * que s'il n'existe pas encore.)
	 */
	private function Categories()
	{
		$temporaire	= array();
		$requete	= tep_db_query('SELECT DISTINCT parent_id, categories_id FROM '.TABLE_CATEGORIES.' ORDER BY categories_id');
		// Une fois la requ�te effectu�e, on stocke temporairement les objets ainsi que l'id de leurs p�res dans un tableau.
		while($temp	= tep_db_fetch_array($requete))
		{
			$temporaire[$temp['categories_id']] = array('obj'	=> new Categorie($temp['categories_id']),
														'pere'	=> $temp['parent_id']);
		}
		reset($temporaire);
		// Finalement, on parcours le tableau et on place les objets dans le tableau index, puis on affecte les fils aux p�res concern�s.
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
	 * Il v�rifie si l'objet est d�j� cr�� et si non, le cr�e une fois pour toute.
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
	 * Cette fonction retourne l'id du p�re de la cat�gorie concern�e.
	 * @param $idCategorie : l'id de la cat�gorie concern�e.
	 * @return (int) l'id du p�re.
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
	
	// Pr�vient les utilisateurs sur le cl�nage de l'instance
    public function __clone()
    {
        trigger_error('Le cl�nage n\'est pas autoris�.', E_USER_ERROR);
    }
}