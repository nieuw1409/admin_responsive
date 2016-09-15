<?php
/**
 * Cette class a pour but de stocker les cat�gories sous forme arborescente.
 * Une cat�gorie connait son p�re ainsi que son/ses fils.
 * 
 * La "racine" de cet arbre est un noeud fictif repr�sent� par null.
 * 
 * @author Adrien Chant�me.
 *
 */
class Categorie
{
	private $id;
	private $peres	= null;
	private $fils	= array();
	
	/**
	 * Le constructeur de la class Categorie.
	 * Il v�rifie que les formats de donn�es soient bon 
	 * et stocke les r�sultats.
	 * 
	 * Si l'id est <= 0, l'objet n'est pas cr�� et la fonction retourne null.
	 * @param $id	 : l'id de la cat�gorie.
	 * @param $peres : le p�res de la cat�gorie.
	 * @param $fils	 : La liste des fils de la cat�gorie.
	 * @return (Categorie) l'objet qui vient d'�tre cr�� ou null si l'id n'etait pas  correcte.
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
	 * V�rifie si le p�re existe d�j�.
	 * @param $pere :  le p�re a v�rifier.
	 * @return (booleen) true si le p�re existe d�j�, false sinon.
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
	 * Affecte une Categorie comme p�re si elle n'y est pas d�j�.
	 * @param $pere : le nouveau p�re
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
	 * V�rifie si le fils existe d�j�.
	 * @param $pere :  le fils a v�rifier.
	 * @return (booleen) true si le fils existe d�j�, false sinon.
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
	 * Affecte une Categorie comme fils si elle n'y est pas d�j�.
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
	 * fonction retournant le tableau des p�res.
	 * @return (Categorie) le tableau des p�res.
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