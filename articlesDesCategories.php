<?php
/**
* Plugin articlesDesCategories
*
* @package	PLX
* @version	1.0
* @date	12/08/17
* @author Cyril MAGUIRE
**/
class articlesDesCategories extends plxPlugin {

	public function __construct($default_lang) {
		# appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);

		# limite l'acces a l'ecran de configuration du plugin
		# PROFIL_ADMIN , PROFIL_MANAGER , PROFIL_MODERATOR , PROFIL_EDITOR , PROFIL_WRITER
		$this->setConfigProfil(PROFIL_ADMIN);

		# Declaration d'un hook (existant ou nouveau)
		$this->addHook('plxShowLastCatList','plxShowLastCatList');
		
	}

	# Activation / desactivation
	public function OnActivate() {
		# code à executer à l’activation du plugin
	}
	public function OnDeactivate() {
		# code à executer à la désactivation du plugin
	}
	

	########################################
	# HOOKS
	########################################


	########################################
	# plxShowLastCatList 
	########################################
	# Description:
	/**
	 * Méthode qui affiche la liste des catégories actives, avec la liste des articles associés.
 	 * @return	stdout
	 * @scope	global
	 * @author	Cyril MAGUIRE
	 */
	public function plxShowLastCatList(){
		$nb_art = $this->getParam('nb_art');
		$string =<<<END
		\$nbSousMenu = $nb_art;
		
		# Si on a la variable extra, on affiche un lien vers la page d'accueil (avec \$extra comme nom)
		if(\$extra != '') {
			\$name = str_replace('#cat_id','cat-home',\$format);
			\$name = str_replace('#cat_url',\$this->plxMotor->urlRewrite(),\$name);
			\$name = str_replace('#cat_name',plxUtils::strCheck(\$extra),\$name);
			\$name = str_replace('#cat_status',(\$this->catId()=='home'?'active':'noactive'), \$name);
			\$name = str_replace('#art_nb','',\$name);
			echo \$name."\n\t\t\t";
		}
		if(\$this->plxMotor->aCats) {
			foreach(\$this->plxMotor->aCats as \$k=>\$v) {
				\$in = (empty(\$include) OR preg_match('/('.\$include.')/', \$k));
				\$ex = (!empty(\$exclude) AND preg_match('/('.\$exclude.')/', \$k));
				if(\$in AND !\$ex) {
				if((\$v['articles']>0 OR \$this->plxMotor->aConf['display_empty_cat']) AND (\$v['menu']=='oui') AND \$v['active']) { # On a des articles
					\$k = intval(\$k);
					if (\$nbSousMenu != 0) :
					ob_start();
					\$this->lastArtList('<li class="#art_status"><a href="#art_url">#art_title</a></li>',\$nbSousMenu,\$k);
					\$sousmenu = ob_get_clean();
					if (strlen(\$sousmenu) != 0):
			            \$sousmenu = '
				<ul>
					'.str_replace('</li><li', '</li>'."\n\t\t\t\t\t".'<li', \$sousmenu).'
				</ul>
			</li>';
					endif;
					else :
						\$sousmenu = '</li>';
					endif;

					# On modifie nos motifs
						\$name = str_replace('#cat_id','menu_cat-'.\$k,\$format);
						\$name = str_replace('#cat_url',\$this->plxMotor->urlRewrite('?categorie'.\$k.'/'.\$v['url']),\$name);
						\$name = str_replace('#cat_name',plxUtils::strCheck(\$v['name']),\$name);
						if (\$this->mode() == 'article' && in_array(\$k,explode(',',\$this->plxMotor->plxRecord_arts->f('categorie')))) {
							\$name = str_replace('#cat_status','active', \$name);
						}else {
							\$name = str_replace('#cat_status',(\$this->catId()==\$k?'active':'noactive'), \$name);
						}
						\$name = str_replace('#art_nb',\$v['articles'],\$name);
						\$name = str_replace('</li>',\$sousmenu,\$name);
						echo \$name;
					}
				}
			} 
		}
		return true;
END;
		echo '<?php '.$string.'?>';
	}
}
