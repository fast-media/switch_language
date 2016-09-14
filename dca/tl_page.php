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
 * List
 */
array_insert(
	$GLOBALS['TL_DCA']['tl_page']['list']['operations'], 1, array(
		'languages' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['tl_page']['languages'],
			'icon'                => 'system/modules/switch_language/assets/language.png',
			'href'                => 'table=tl_page_language',
			'button_callback'     => array('tl_page_extended', 'iconPageLanguage')
		)
	)
);


class tl_page_extended extends tl_page
{

	/**
	 * Return the "language element" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function iconPageLanguage($row, $href, $label, $title, $icon, $attributes)
	{

    $href .= '&amp;id='.$row['id'];

    $variants = $this->Database->prepare("SELECT id FROM tl_page_language WHERE pid=?")->execute($row['id']);

		if (!$variants->id)
		{
			$icon = 'system/modules/switch_language/assets/language_.png';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}
}
