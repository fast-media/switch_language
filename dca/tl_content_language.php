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

$strTable = 'tl_content_language';

/**
 * Table tl_content_filter
 */
$GLOBALS['TL_DCA'][$strTable] = array
(

	// Config
	'config' => array
	(
		'dataContainer'					=> 'Table',
		'ptable'								=> 'tl_content',
		'label'							=> &$GLOBALS['TL_LANG']['MOD']['contentcategories'][0],
		'switchToEdit'								=> true,
		'enableVersioning'						=> true,
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
			'fields'						=> array('note'),
			'flag'							=> 1,
			'panelLayout'				=> 'search,limit'
		),
		'label' => array
		(
			'fields'						=> array('language', 'language_short', 'headline', 'note'),
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
				'button_callback'	=> array($strTable, 'copyType')
			),
			'delete' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['delete'],
				'href'						=> 'act=delete',
				'icon'						=> 'delete.gif',
				'attributes'			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'	=> array($strTable, 'deleteType')
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
		'default'							=> '{language_legend},language,note;{title_legend},headline,headline_reference;{text_legend},text,text_reference',
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
			'foreignKey'				=> 'tl_content.title',
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'relation'					=> array('type'=>'belongsTo', 'load'=>'eager')
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
		'headline' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['headline'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>200, 'tl_class'=>'w50'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'headline_reference' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['headline_reference'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'load_callback'			=> array(array($strTable, 'headlineReference')),
			'eval'							=> array('disabled' => true, 'tl_class'=>'w50')
		),
		'text' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['text'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'textarea',
			'eval'							=> array('rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'				=> 'insertTags',
			'sql'								=> "text NULL"
		),
		'text_reference' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['text_reference'],
			'exclude'						=> true,
			'inputType'					=> 'textarea',
			'load_callback'			=> array(array($strTable, 'textReference')),
			'eval'							=> array('disabled' => true, 'rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'				=> 'insertTags'
		)
	)
);


class tl_content_language extends \Backend
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
	 * Check permissions to edit table tl_content_language
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		if (!$this->User->isAdmin && !$this->User->hasAccess('show', 'contentcatp')) {
			$this->redirect('contao/main.php?act=error');
		}

		if (!$this->User->hasAccess('create', 'contentcatp'))
		{
			$GLOBALS['TL_DCA']['tl_content_language']['config']['closed'] = true;
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
					if (!$this->User->hasAccess('create', 'contenttypp'))
					{
						$this->log('Not enough permissions to '.Input::get('act').' content typegory ID "'.Input::get('id').'"', 'tl_content_languagegory checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
				}

				// Add permissions on group level
			elseif ($this->User->groups[0] > 0)
				{
					$objGroup = $this->Database->prepare("SELECT contenttypp FROM tl_user_group WHERE id=?")
										->limit(1)
										->execute($this->User->groups[0]);

					$arrCalendartypp = deserialize($objGroup->contenttypp);

					if (is_array($arrCalendartypp) && in_array('create', $arrCalendartypp))
					{
					}
					else {
						$this->log('Not enough permissions to '.Input::get('act').' content typegory ID "'.Input::get('id').'"', 'tl_content_languagegory checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
				}

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array(Input::get('id'), $root) || (Input::get('act') == 'delete' && !$this->User->hasAccess('delete', 'contenttypp')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' contenttyps archive ID "'.Input::get('id').'"', 'tl_content_language checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if (Input::get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'contenttypp'))
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
					$this->log('Not enough permissions to '.Input::get('act').' contenttyps archives', 'tl_content_language checkPermission', TL_ERROR);
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
		return ($this->User->isAdmin || count(preg_grep('/^tl_content_language::/', $this->User->alexf)) > 0) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the edit category button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editType($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'contenttypp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
	public function copyType($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'contenttypp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
	public function deleteType($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'contenttypp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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


	public function headlineReference($varValue, DataContainer $dc)
	{
		$objElement = $this->Database->prepare("SELECT headline FROM tl_content WHERE id=?")->execute($dc->activeRecord->pid);

		$arrHeadline = deserialize($objElement->headline);
		$varValue = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;

		return $varValue;
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
	public function textReference($varValue, DataContainer $dc)
	{
		$objElement = $this->Database->prepare("SELECT text FROM tl_content WHERE id=?")->execute($dc->activeRecord->pid);

		$varValue = $objElement->text;

		return $varValue;
	}


	/**
	 * Add a link to the list items import wizard
	 * @return string
	 */
	public function listImportWizard()
	{
		return ' <a href="' . $this->addToUrl('key=list') . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['lw_import'][1]) . '" onclick="Backend.getScrollOffset()">' . Image::getHtml('tablewizard.gif', $GLOBALS['TL_LANG']['MSC']['tw_import'][0], 'style="vertical-align:text-bottom"') . '</a>';
	}


}
