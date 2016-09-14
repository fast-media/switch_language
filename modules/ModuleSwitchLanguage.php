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


class ModuleSwitchLanguage extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_switch_language';

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['switch_language'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;

		$strLanguage = \Input::get('language');
		if(!$strLanguage) {
			$strLanguage = $GLOBALS['TL_LANGUAGE'];
		}

		$arrPages = array();

		$arrPages[] = array(
			'default' => true,
			'title' => $objPage->title,
      'alias' => $objPage->alias,
			'language' => $objPage->language,
      'pageTitle' => $objPage->pageTitle,
		);

		// Required for the current pagetree language
		$objTranslation = \Database::getInstance()->prepare("SELECT * FROM tl_page_language WHERE pid=?")->execute($objPage->id);
		while($objTranslation->next()) {
			$arrPages[$objTranslation->language] = array(
				'title' => $objTranslation->title,
				'alias' => $objTranslation->alias,
				'language' => $objTranslation->language,
      	'pageTitle' => $objTranslation->pageTitle,
			);
		}

		$arrItems = array();
		$c = 0;
		$count = count($arrPages);

		foreach ($arrPages as $arrPage)
		{
			$active = false;

			if($arrPage['language'] == $strLanguage) {
				$active = true;
			}


      if (\Config::get('i18nl10n_urlParam') === 'url') {
				$strUrl = $arrPage['language'] . '/'.$arrPage['alias'] . \Config::get('urlSuffix');
			}
			else {

				if($arrPage['default']) {
					$strUrl = $objPage->alias . \Config::get('urlSuffix');
				}

        else {
					$strUrl = $objPage->alias . \Config::get('urlSuffix') . '?language='.$arrPage['language'];
				}
			}

	    if(!$arrPage['pageTitle']) {
	      $strPageTitle = $arrPage['title'];
			}

	    else {
				$strPageTitle = $arrPage['pageTitle'];
			}

			// Build template array
			$arrItems[$c] = array
			(
					'isActive'	=> $active,
					'class'		=> 'lang-' . $arrPage['language'] . ($active ? ' active' : '') . ($c == 0 ? ' first' : '') . ($c == $count-1 ? ' last' : ''),
					'link'		=> strtoupper($arrPage['language']),
					'subitems'	=> '',
					'href'		=> $strUrl,
					'pageTitle' => strip_tags($strPageTitle),
					'accesskey'	=> '',
					'tabindex'	=> '',
					'nofollow'	=> false,
					'target'	=> $target . ' hreflang="' . $arrPage['language'] . '" lang="' . $arrPage['language'] . '"',
					'language'	=> $arrPage['language'],
			);

			$c++;
		}

		$objTemplate = new \FrontendTemplate($this->navigationTpl);
		$objTemplate->level = 'level_1';
		$objTemplate->items = $arrItems;

		$this->Template->items = $objTemplate->parse();

	}
}
