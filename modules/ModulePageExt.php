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

	public function pageChange($objTemplate) {
    global $objPage;

		$strLanguage = \Input::get('language');
		if(!$strLanguage) {
      $strLanguage = $GLOBALS['TL_LANGUAGE'];
		}

    if (stristr($objTemplate->getName(),'ce_')) {

			global $objPage;

      $objLanguage = $this->Database->prepare("SELECT id, title, pageTitle, description FROM tl_page_language WHERE pid=? AND language=?")->execute($objPage->id, $strLanguage);

      if($objLanguage->id) {

				if ($objLanguage->pageTitle != '')
				{
          $objPage->pageTitle = $objLanguage->pageTitle;
				}
				elseif ($objLanguage->title != '')
				{
          $objPage->pageTitle = $objLanguage->title;
				}

				if ($objLanguage->description != '')
				{
          $objPage->description = $objLanguage->description;
				}
			}
		}


    if (stristr($objTemplate->getName(),'nav_')) {

			if($objTemplate->items) {

        $arrItems = array();
				foreach($objTemplate->items AS $key => $item) {
          $go_on = false;
					//Sprachen Switch für die Navigation
		      $objLanguage = $this->Database->prepare("SELECT id, title, alias FROM tl_page_language WHERE pid=? AND language=?")->execute($item['id'], $strLanguage);
		      if($objLanguage->id) {

						if ($objLanguage->title != '')
						{
              $arrReplace = array('link' => $objLanguage->title);
              //$arrReplace2 = array('href' => $objLanguage->alias.'/'); // Geht derzeit nicht, weil dann immer 404 kommt!!!
              $arrReplace2 = array('href' => $item['alias'] . \Config::get('urlSuffix') . '?language=' . $strLanguage);
              $arrItems[] = array_replace($item, $arrReplace, $arrReplace2);
							$go_on = true;
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
					if(!$go_on) {
            $arrItems[] = $item;
					}
				}
        $objTemplate->items = $arrItems;
			}

		}
	}
}
