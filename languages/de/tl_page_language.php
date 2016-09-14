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
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Name der Kategorie', 'Bitte geben Sie hier die Übersetzung der Kategorie für die aktuelle Sprache ein.');
$GLOBALS['TL_LANG'][$strTable]['language'] = array('Sprache', 'Bitte wählen Sie eine Sprache aus, die von Ihrer Standard-Sprache abweicht.');
$GLOBALS['TL_LANG'][$strTable]['language_short'] = array('Kürzel', 'Das Sprachkürzel.');

$GLOBALS['TL_LANG'][$strTable]['translation'] = array('Übersetzung', 'Diese Bereiche wurden bereits übersetzt.');

$GLOBALS['TL_LANG'][$strTable]['note'] = array('Interne Notiz', 'Sie können hier einen Kommentar hinterlassen, um anderen Redakteuren wichtige Hinweise zu geben.');

$GLOBALS['TL_LANG'][$strTable]['title'] = array('Seitenname', 'Bitte geben Sie hier die Übersetzung des Seitennamens ein.');
$GLOBALS['TL_LANG'][$strTable]['alias'] = array('Seitenalias', 'Bitte geben Sie hier die Übersetzung des Seitenalias ein.');
$GLOBALS['TL_LANG'][$strTable]['pageTitle'] = array('Seitentitel', 'Bitte geben Sie hier die Übersetzung des Titels der Seite ein.');
$GLOBALS['TL_LANG'][$strTable]['description'] = array('Beschreibung der Seite', 'Bitte geben Sie hier die Übersetzung des Seitenbeschreibung ein (Meta).');

$GLOBALS['TL_LANG'][$strTable]['title_reference'] = array('Original Seitenname', 'Sie sehen hier den Original Seitennamen. Änderungen werden nicht gespeichert.');
$GLOBALS['TL_LANG'][$strTable]['alias_reference'] = array('Original Seitenalias', 'Sie sehen hier den Original Seitenalias. Änderungen werden nicht gespeichert.');
$GLOBALS['TL_LANG'][$strTable]['pageTitle_reference'] = array('Original Seitentitel', 'Sie sehen hier den Original Seitentitel. Änderungen werden nicht gespeichert.');
$GLOBALS['TL_LANG'][$strTable]['description_reference'] = array('Original Beschreibung der Seite', 'Sie sehen hier den Original Seitennamen. Änderungen werden nicht gespeichert.');

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
$GLOBALS['TL_LANG'][$strTable]['language_legend'] = 'Sprache';
$GLOBALS['TL_LANG'][$strTable]['translation_legend'] = 'Übersetzung';
