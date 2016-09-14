<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   switch_language
 * @author    Fast & Media | Christian Schmidt <c.schmidt@fast-end-media.de>
 * @license   LGPL
 * @copyright Fast & Media 2015-2016 <https://www.fast-end-media.de>
 */


/**
 * Hooks
 */

if (TL_MODE == 'FE')
{
	$GLOBALS['TL_HOOKS']['parseTemplate'][] = array('SwitchLanguage\ModuleContentExt', 'contentChange');
  $GLOBALS['TL_HOOKS']['parseTemplate'][] = array('SwitchLanguage\ModulePageExt', 'pageChange');
  //$GLOBALS['TL_HOOKS']['generatePage'][] = array('SwitchLanguage\ModulePageExt', 'onGeneratePage');
}

//Inhaltselemente
$GLOBALS['BE_MOD']['content']['article']['tables'][] = 'tl_content_language';
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_content_language';
$GLOBALS['BE_MOD']['content']['news']['tables'][] = 'tl_content_language';
$GLOBALS['BE_MOD']['content']['faq']['tables'][] = 'tl_content_language';

//Check if extension 'event_manager' is installed
if (in_array('simple_products_shop', $this->getActiveModules()))
{
	$GLOBALS['BE_MOD']['simple_products']['product']['tables'][] = 'tl_content_language';
}

//Layout
$GLOBALS['BE_MOD']['design']['page']['tables'][] = 'tl_page_language';


/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['miscellaneous']['switch_language'] = 'ModuleSwitchLanguage';


/**
 * HOOKS
 */
$strClassName = '\SwitchLanguage\FrontendHook';

$GLOBALS['TL_HOOKS']['generateFrontendUrl'][] = array($strClassName, 'generateFrontendUrl');
$GLOBALS['TL_HOOKS']['getPageIdFromUrl'][]    = array($strClassName, 'getPageIdFromUrl');
