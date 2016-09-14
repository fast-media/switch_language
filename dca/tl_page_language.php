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

$strTable = 'tl_page_language';

/**
 * Table tl_product_filter
 */
$GLOBALS['TL_DCA'][$strTable] = array
(

	// Config
	'config' => array
	(
		'dataContainer'				=> 'Table',
		'ptable'							=> 'tl_page',
		'label'								=> &$GLOBALS['TL_LANG']['MOD']['productcategories'][0],
		'switchToEdit'				=> true,
		'enableVersioning'		=> true,
		'onload_callback' => array
		(
			array($strTable, 'checkPermission')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'							=> 1,
			'fields'						=> array('language'),
			'flag'							=> 1,
			'panelLayout'				=> 'search,limit'
		),
		'label' => array
		(
			'fields'						=> array('language', 'language_short', 'title', 'translation', 'note'),
			'showColumns'				=> true,
			'label_callback'		=> array($strTable, 'getRowLabel')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'						=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'						=> 'act=select',
				'class'						=> 'header_edit_all',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['edit'],
				'href'						=> 'act=edit',
				'icon'						=> 'edit.gif'
			),
			'copy' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['copy'],
				'href'						=> 'act=copy',
				'icon'						=> 'copy.gif',
				'button_callback'	=> array($strTable, 'copyLanguage')
			),
			'delete' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['delete'],
				'href'						=> 'act=delete',
				'icon'						=> 'delete.gif',
				'attributes'			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'	=> array($strTable, 'deleteLanguage')
			),
			'show' =>array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['show'],
				'href'						=> 'act=show',
				'icon'						=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'				=> array(),
		'default'							=> '{language_legend},language,note;{translation_legend},title,title_reference,alias,alias_reference,pageTitle,pageTitle_reference,description,description_reference',
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'								=> "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'							=> 'tl_product.title',
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'relation'								=> array('type'=>'belongsTo', 'load'=>'eager')
		),
		'sorting' => array
		(
			'sql'								=> "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'								=> "int(10) unsigned NOT NULL default '0'"
		),
		'note' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['note'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>255),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'language_short' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['language_short']
		),
		'translation' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['translation']
		),
		'language' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['language'],
			'exclude'						=> true,
			'filter'						=> true,
			'inputType'					=> 'select',
			'options_callback'	=> array($strTable, 'getLanguages'),
			'eval'							=> array(),
			'sql'								=> "varchar(32) NOT NULL default ''"
		),
		'title' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['title'],
			'exclude'						=> true,
			'search'						=> true,
			'sorting'						=> true,
			'flag'							=> 1,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'title_reference' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['title_reference'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'load_callback'			=> array(array($strTable, 'languageReference')),
			'eval'							=> array('disabled' => true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'alias' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['alias'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'search'						=> true,
			'eval'							=> array('rgxp'=>'folderalias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback'			=> array
			(
				array($strTable, 'generateAlias')
			),
			'sql'								=> "varchar(128) COLLATE utf8_bin NOT NULL default ''"
		),
		'alias_reference' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['alias_reference'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'load_callback'			=> array(array($strTable, 'languageReference')),
			'eval'							=> array('disabled' => true, 'rgxp'=>'folderalias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50')
		),
		'pageTitle' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['pageTitle'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'search'						=> true,
			'eval'							=> array('maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'pageTitle_reference' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['pageTitle_reference'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'load_callback'			=> array(array($strTable, 'languageReference')),
			'eval'							=> array('disabled' => true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'description' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['description'],
			'exclude'						=> true,
			'inputType'					=> 'textarea',
			'search'						=> true,
			'eval'							=> array('style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr'),
			'sql'								=> "text NULL"
		),
		'description_reference' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['description_reference'],
			'exclude'						=> true,
			'inputType'					=> 'textarea',
			'load_callback'			=> array(array($strTable, 'languageReference')),
			'eval'							=> array('style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr')
		)
	)
);


class tl_page_language extends \Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Check permissions to edit table tl_page_language
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		if (!$this->User->isAdmin && !$this->User->hasAccess('show', 'productcatp')) {
			$this->redirect('contao/main.php?act=error');
		}

		if (!$this->User->hasAccess('create', 'productcatp'))
		{
			$GLOBALS['TL_DCA']['tl_page_language']['config']['closed'] = true;
		}

		// Check current action
		switch (Input::get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Add permissions on user level
				if ($this->User->inherit == 'custom' || !$this->User->groups[0])
				{
					if (!$this->User->hasAccess('create', 'producttypp'))
					{
						$this->log('Not enough permissions to '.Input::get('act').' product typegory ID "'.Input::get('id').'"', 'tl_page_languagegory checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
				}

				// Add permissions on group level
			elseif ($this->User->groups[0] > 0)
				{
					$objGroup = $this->Database->prepare("SELECT producttypp FROM tl_user_group WHERE id=?")
										->limit(1)
										->execute($this->User->groups[0]);

					$arrCalendartypp = deserialize($objGroup->producttypp);

					if (is_array($arrCalendartypp) && in_array('create', $arrCalendartypp))
					{
					}
					else {
						$this->log('Not enough permissions to '.Input::get('act').' product typegory ID "'.Input::get('id').'"', 'tl_page_languagegory checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
				}

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array(Input::get('id'), $root) || (Input::get('act') == 'delete' && !$this->User->hasAccess('delete', 'producttypp')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' producttyps archive ID "'.Input::get('id').'"', 'tl_page_language checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if (Input::get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'producttypp'))
				{
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen(Input::get('act')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' producttyps archives', 'tl_page_language checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	public function getRowLabel($row, $label, DataContainer $dc, $args)
	{
		$this->loadLanguageFile('languages');
		$args[0] = $GLOBALS['TL_LANG']['LNG'][$row['language']];

		$args[1] = $row['language'];

		//Übersetzte Felder anzeigen
		if($row['pageTitle']) { $fields = '<div><input type="checkbox" value="1" checked="checked" disabled> Seitentitel</div>';}
		if($row['description']) { $fields .= '<div><input type="checkbox" value="1" checked="checked" disabled> Beschreibung der Seite</div>';}
		$args[3] = $fields;

		return $args;
	}


	/**
	 * Return the edit header button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^tl_page_language::/', $this->User->alexf)) > 0) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the copy category button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyLanguage($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'producttypp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}

	/**
	 * Return the delete archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteLanguage($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'producttypp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function languageReference($varValue, DataContainer $dc)
	{
		$strField = strstr($dc->field, '_', true);
		if($GLOBALS['TL_DCA']['tl_page_language']['fields'][$strField]) {
			$varValue = $this->Database->prepare("SELECT $strField FROM tl_page WHERE id=?")->execute($dc->activeRecord->pid)->$strField;
		}

		//$varValue = $objElement->description;

		return $varValue;
	}

	/**
	 * Auto-generate a page alias if it has not been set yet
	 *
	 * @param mixed		$varValue
	 * @param DataContainer $dc
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate an alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = standardize(String::restoreBasicEntities($dc->activeRecord->title));

			// Generate folder URL aliases (see #4933)
			if (Config::get('folderUrl'))
			{
				$objPage = PageModel::findWithDetails($dc->activeRecord->id);

				if ($objPage->folderUrl != '')
				{
					$varValue = $objPage->folderUrl . $varValue;
				}
			}
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_page_language WHERE id=? OR alias=?")
							->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > ($autoAlias ? 0 : 1))
		{
			$arrPages = array();
			$strDomain = '';
			$strLanguage = '';

			while ($objAlias->next())
			{
				$objCurrentPage = PageModel::findWithDetails($objAlias->id);
				$domain = $objCurrentPage->domain ?: '*';
				$language = (!$objCurrentPage->rootIsFallback) ? $objCurrentPage->rootLanguage : '*';

				// Store the current page's data
				if ($objCurrentPage->id == $dc->id)
				{
					// Get the DNS and language settings from the POST data (see #4610)
					if ($objCurrentPage->type == 'root')
					{
						$strDomain = Input::post('dns');
						$strLanguage = Input::post('language');
					}
					else
					{
						$strDomain = $domain;
						$strLanguage = $language;
					}
				}
				else
				{
					// Check the domain and language or the domain only
					if (Config::get('addLanguageToUrl'))
					{
						$arrPages[$domain][$language][] = $objAlias->id;
					}
					else
					{
						$arrPages[$domain][] = $objAlias->id;
					}
				}
			}

			$arrCheck = Config::get('addLanguageToUrl') ? $arrPages[$strDomain][$strLanguage] : $arrPages[$strDomain];

			// Check if there are multiple results for the current domain
			if (!empty($arrCheck))
			{
				if ($autoAlias)
				{
					$varValue .= '-' . $dc->id;
				}
				else
				{
					throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
				}
			}
		}

		return $varValue;
	}
}
