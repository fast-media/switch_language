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
 * Run in a custom namespace, so the class can be replaced
 */
namespace SwitchLanguage;


class ModulePageExt extends \Frontend
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_text';

	public function onGeneratePage($objPage, $objLayout, $objPageRegular) {
echo $objPage->id;
//print_r($objTemplate);
		//Sprachen Switch für Produkte
    $objLanguage = $this->Database->prepare("SELECT * FROM tl_page_language WHERE pid=? AND language=?")->execute($objPage->id, $GLOBALS['TL_LANGUAGE']);
    if($objLanguage->id) {
		  //$this->Template->pageTitle
			if ($objLanguage->title != '')
			{
        $objPage->title = $objLanguage->title;
			}

			// Compile the product text
			if ($objLanguage->text != '') {
				if ($objPage->outputFormat == 'xhtml')
				{
					$objLanguage->text = \String::toXhtml($objLanguage->text);
				}
				else
				{
					$objLanguage->text = \String::toHtml5($objLanguage->text);
				}

				$objTemplate->text = \String::encodeEmail($objLanguage->text);
			}

		}

	}
}
