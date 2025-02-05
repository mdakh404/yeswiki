<?php
/*vim: set expandtab tabstop=4 shiftwidth=4: */
// +------------------------------------------------------------------------------------------------------+
// | PHP version 5                                                                                        |
// +------------------------------------------------------------------------------------------------------+
// | Copyright (C) 2012 Outils-Réseaux (accueil@outils-reseaux.org)                                       |
// +------------------------------------------------------------------------------------------------------+
// | This library is free software; you can redistribute it and/or                                        |
// | modify it under the terms of the GNU Lesser General Public                                           |
// | License as published by the Free Software Foundation; either                                         |
// | version 2.1 of the License, or (at your option) any later version.                                   |
// |                                                                                                      |
// | This library is distributed in the hope that it will be useful,                                      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of                                       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU                                    |
// | Lesser General Public License for more details.                                                      |
// |                                                                                                      |
// | You should have received a copy of the GNU Lesser General Public                                     |
// | License along with this library; if not, write to the Free Software                                  |
// | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA                            |
// +------------------------------------------------------------------------------------------------------+
//
/**
* Fichier de traduction en francais de l'extension Hashcash
*
* @package 		hashcash
* @author        Florian Schmitt <florian@outils-reseaux.org>
* @copyright     2012 Outils-Réseaux
*/

$GLOBALS['translations'] = array_merge($GLOBALS['translations'], array(

'HASHCASH_ERROR_PAGE_UNSAVED' => '<strong>La page ne peut pas &ecirc;tre enregistr&eacute;e.</strong><br />Vous avez peut-&ecirc;tre double cliqu&eacute; sur le bouton "Sauver", entrainant 2 sauvegardes cons&eacute;cutives trop rapproch&eacute;es, ou laiss&eacute; la page ouverte en mode &eacute;dition trop longtemps.<br />Pour enregistrer vos modifications, veuillez copier le contenu, rafraichir la page et coller votre page modifi&eacute;e &agrave; nouveau.',
'HASHCASH_ANTISPAM_ACTIVATED' => 'Protection anti-spam active',
'HASHCASH_COMMENT_NOT_SAVED_MAYBE_YOU_ARE_A_ROBOT' => 'Votre commentaire n\'a pas &eacute;t&eacute; enregistr&eacute;, le wiki pense que vous êtes un robot.',
'HASHCASH_GENERAL_PASSWORD' => 'Réponse&nbsp;:&nbsp;',
'HASHCASH_SEND' => 'Envoyer',
'CAPTCHA_ERROR_PAGE_UNSAVED' => 'La page n\'a pas été enregistrée car vous n\'avez pas rentré le mot de vérification.',
'CAPTCHA_ERROR_WRONG_WORD' => 'La page n\'a pas été enregistrée car le mot de vérification rentré n\'est pas correct.',
'CAPTCHA_VERIFICATION' => 'V&eacute;rification pour sauver la page',
'CAPTCHA_WRITE' => 'Ecrire ici le mot présent dans l\'image',

// for edit config
'EDIT_CONFIG_HINT_USE_CAPTCHA' => 'Activer l\'utilisation d\'un captcha avant la sauvegarde (1 ou 0)',
'EDIT_CONFIG_HINT_USE_HASHCASH' => 'Activer l\'antispam hashcash du wiki (activé par défaut)',
'EDIT_CONFIG_HINT_USE_ALERTE' => 'Prévenir si l\'on quitte la page sans sauvegarder (1 ou 0)',
'EDIT_CONFIG_HINT_WIKI_STATUS' => 'État du wiki (running ou vide = standard, hibernate = lecture seule)',
'EDIT_CONFIG_GROUP_SECURITY' => 'Sécurité',

// security controller
'WIKI_IN_HIBERNATION' => 'Action désactivée car ce wiki est en lecture seule. Veuillez contacter l\'administrateur pour le réactiver.',
));
