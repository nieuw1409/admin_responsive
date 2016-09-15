<?php
/**
 * Cette class a pour but de stocker les catégories sous forme arborescente.
 * Une catégorie connait son père ainsi que son/ses fils.
 * 
 * La "racine" de cet arbre est un noeud fictif représenté par null.
 * 
 * @author Adrien Chantôme.
 *
 */
class Categorie
{
	private $id;
	private $peres	= null;
	private $fils	= array();
	
	/**
	 * Le constructeur de la class Categorie.
	 * Il vérifie que les formats de données soient bon 
	 * et stocke les résultats.
	 * 
	 * Si l'id est <= 0, l'objet n'est pas créé et la fonction retourne null.
	 * @param $id	 : l'id de la catégorie.
	 * @param $peres : le pères de la catégorie.
	 * @param $fils	 : La liste des fils de la catégorie.
	 * @return (Categorie) l'objet qui vient d'être créé ou null si l'id n'etait pas  correcte.
	 */
	public function Categorie($id, $peres = null, $fils = array())
	{
		if($id <= 0)
		{
			return null;
		}
		if(!is_array($fils))
		{
			$fils = array();
		}
		$this->id		= $id;
		$this->peres	= $peres;
		$this->fils		= $fils;
		if($peres != null)
		{
			$peres->setFils($this);
		}
		foreach($fils as $f)
		{
			$f->setPere($this);
		}
	}
	
	/**
	 * Vérifie si le père existe déjà.
	 * @param $pere :  le père a vérifier.
	 * @return (booleen) true si le père existe déjà, false sinon.
	 */
	public function isPere($pere)
	{
		if($this->pere == null || $pere == null)
		{
			return false;
		}
		if($this->pere->getId() == $pere->getId())
		{
			return true;
		}
		return false;
	}
	
	/**
	 * Affecte une Categorie comme père si elle n'y est pas déjà.
	 * @param $pere : le nouveau père
	 */
	public function setPere($pere)
	{
		if(!$this->isPere($pere))
		{
			$this->peres = $pere;
			$pere->setFils($this);
		}
	}
	
	/**
	 * Vérifie si le fils existe déjà.
	 * @param $pere :  le fils a vérifier.
	 * @return (booleen) true si le fils existe déjà, false sinon.
	 */
	public function isFils($fils)
	{
		if($fils == null)
		{
			return false;
		}
		foreach($this->fils as $f)
		{
			if($f->getId() == $fils->getId())
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Affecte une Categorie comme fils si elle n'y est pas déjà.
	 * @param $fils : le nouveau fils
	 */
	public function setFils($fils)
	{
		if(!$this->isFils($fils))
		{
			$this->fils[] = $fils;
			$fils->setPere($this);
		}
	}
	
	/**
	 * fonction retournant l'id de l'objet.
	 * @return (int) l'id.
	 */
	public function getId()
	{
		return (int)$this->id;
	}
	
	/**
	 * fonction retournant le tableau des pères.
	 * @return (Categorie) le tableau des pères.
	 */
	public function getPere()
	{
		return $this->peres;
	}
	
	/**
	 * fonction retournant le tableau des fils.
	 * @return (array(Categorie)) le tableau des fils.
	 */
	public function getFils()
	{
		return $this->fils;
	}
}