<?php 
/**
* Plugin articlesDesCategories
*
* @package  PLX
* @version  1.0
* @date 12/08/17
* @author Cyril MAGUIRE
**/
if(!defined("PLX_ROOT")) exit; ?>
<?php 
     if(!empty($_POST)) {
        $plxPlugin->setParam("nb_art", plxUtils::strCheck($_POST["nb_art"]), "numeric");
        $plxPlugin->saveParams();
        header("Location: parametres_plugin.php?p=articlesDesCategories");
        exit;
    }
?>

<p><?php $plxPlugin->lang("L_DESCRIPTION") ?></p>
<form action="parametres_plugin.php?p=articlesDesCategories" method="post" >

    <p>
        <label><?php $plxPlugin->lang("L_NB_ART") ?> : 
            <input type="text" name="nb_art" value="<?php echo ($plxPlugin->getParam("nb_art") <= 0 ? 15 : $plxPlugin->getParam("nb_art"));?>" size="2"/>
        </label>
    </p>
    <br />
    <input type="submit" name="submit" value="<?php $plxPlugin->lang("L_REC") ?>"/>
</form>