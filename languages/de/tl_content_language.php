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
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Produktname', 'Bitte geben Sie hier die Übersetzung des Produktnamens für die aktuelle Sprache ein. Wenn Sie das Feld leer lassen, wird der Original Produktname auch in dieser Sprache angezeigt.');
$GLOBALS['TL_LANG'][$strTable]['language'] = array('Sprache', 'Bitte wählen Sie eine Sprache aus, die von Ihrer Standard-Sprache abweicht.');
$GLOBALS['TL_LANG'][$strTable]['language_short'] = array('Kürzel', 'Das Sprachkürzel.');
$GLOBALS['TL_LANG'][$strTable]['note'] = array('Interne Notiz', 'Sie können hier einen Kommentar hinterlassen, um anderen Redakteuren wichtige Hinweise zu geben.');

$GLOBALS['TL_LANG'][$strTable]['headline'] = array('Überschrift', 'Bitte geben Sie hier die Übersetzung der Überschrift ein.');
$GLOBALS['TL_LANG'][$strTable]['headline_reference'] = array('Original Überschrift', 'Sie sehen hier die Original Überschrift. Änderungen werden nicht gespeichert.');

$GLOBALS['TL_LANG'][$strTable]['text'] = array('Text', 'Bitte geben Sie hier die Übersetzung des Textes von unten ein.');
$GLOBALS['TL_LANG'][$strTable]['text_reference'] = array('Original Text', 'Sie sehen hier den Originaltext. Änderungen werden nicht gespeichert.');

$GLOBALS['TL_LANG'][$strTable]['published'] = array('Übersetzung veröffentlichen', 'Das Übersetzung im Backend anzeigen.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['new'] = array('Neue Übersetzung', 'Eine neue Übersetzung anlegen');
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Übersetzung verwalten', 'Die Übersetzung ID %s bearbeiten');
$GLOBALS['TL_LANG'][$strTable]['copy'] = array('Übersetzung duplizieren', 'Die Übersetzung ID %s duplizieren.');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Übersetzung löschen', 'Die Übersetzung ID %s löschen');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Details ansehen', 'Details zu Übersetzung ID %s');


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['language_legend']	= 'Sprache';

$GLOBALS['TL_LANG'][$strTable]['title_legend'] = 'Überschrift';
$GLOBALS['TL_LANG'][$strTable]['text_legend'] = 'Text/HTML/Code';
