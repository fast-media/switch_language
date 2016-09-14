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
 * @copyright   Copyright (c) 2014-2015 Verstärker, Patric Eberle
 * @author      Patric Eberle <line-in@derverstaerker.ch>
 * @package     i18nl10n classes
 * @license     LGPLv3 http://www.gnu.org/licenses/lgpl-3.0.html
 */

namespace SwitchLanguage;


class FrontendHook extends \System
{
    /**
     * Initialize class
     */
    function __construct()
    {
        // Import database handler
        $this->import('Database');
    }

    /**
     * Generates url for the site according to settings from the backend
     *
     * @param array  $arrRow
     * @param string $strParams
     * @param string $strUrl
     *
     * @return string
     *
     * @throws \Exception
     */
    public function generateFrontendUrl($arrRow, $strParams, $strUrl)
    {
        if (!is_array($arrRow)) {
            throw new \Exception('not an associative array.');
        }

				global $objPage;

				$strLanguageDefault = $this->Database->prepare("SELECT language FROM tl_page WHERE id=?")->execute($objPage->rootId)->language;

        $arrL10nAlias = null;

        // Append language if existing and forced (by i18nl10n)
        $language     = empty($arrRow['language']) || empty($arrRow['forceRowLanguage'])
            ? $GLOBALS['TL_LANGUAGE']
            : $arrRow['language'];

        // try to get l10n alias by language and pid
        if ($language !== $strLanguageDefault) {
            $arrL10nAlias = $this->Database
                ->prepare('SELECT alias FROM tl_page_i18nl10n WHERE pid = ? AND language = ?')
                ->execute($arrRow['id'], $language)
                ->fetchAssoc();
        }

        $alias = is_array($arrL10nAlias)
            ? $arrL10nAlias['alias']
            : $arrRow['alias'];

        // Remove auto_item and language
        $regex = '@/auto_item|/language/[a-z]{2}|[\?&]language=[a-z]{2}@';
        $strParams = preg_replace($regex, '', $strParams);
        $strUrl    = preg_replace($regex, '', $strUrl);

        // If alias is disabled add language to get param end return
        if (\Config::get('disableAlias')) {
            $missingValueRegex = '@(.*\?[^&]*&)([^&]*)=(?=$|&)(&.*)?@';

            if (\Config::get('useAutoItem') && preg_match($missingValueRegex, $strUrl) == 1) {
                $strUrl = preg_replace($missingValueRegex, '${1}auto_item=${2}${3}', $strUrl);
            }

            return $strUrl . '&language=' . $language;
        }

        if (\Config::get('i18nl10n_urlParam') === 'alias' && !\Config::get('disableAlias')) {

            $strL10nUrl = $alias . $strParams . '.' . $language . \Config::get('urlSuffix');

            // if rewrite is off, add environment
            if (!\Config::get('rewriteURL')) {
                $strL10nUrl = 'index.php/' . $strL10nUrl;
            }
        } elseif (\Config::get('i18nl10n_urlParam') === 'url') {

            $strL10nUrl = $language . '/' . $alias . $strParams . \Config::get('urlSuffix');

            // if rewrite is off, add environment
            if (!\Config::get('rewriteURL')) {
                $strL10nUrl = 'index.php/' . $strL10nUrl;
            }

            // if alias is missing (f.ex. index.html), add it (exclude news!)
            // search for
            // www.domain.com/
            // www.domain.com/foo/
            if (!\Config::get('disableAlias')
                && preg_match(
                       '@' . $arrRow['alias'] . '(?=\\' . \Config::get('urlSuffix') . '|/)@',
                       $strL10nUrl
                   ) === false
            ) {
                $strL10nUrl .= $alias . \Config::get('urlSuffix');
            }

        } else {
            $strL10nUrl = str_replace($arrRow['alias'], $alias, $strUrl);

            // Check if params exist
            if (strpos($strL10nUrl, '?') !== false) {
                if (strpos($strL10nUrl, 'language=') !== false) {
                    // if variable 'language' replace it
                    $regex      = '@language=[a-z]{2}@';
                    $strL10nUrl = preg_replace(
                        $regex,
                        'language=' . $language,
                        $strL10nUrl
                    );
                } else {
                    // If no variable 'language' add it
                    $strL10nUrl .= '&language=' . $language;
                }
            } else {
                // If no variables define variable 'language'
                $strL10nUrl .= '?language=' . $language;
            }
        }
//print_r($strL10nUrl.'<br>');
        return $strL10nUrl;
    }

    /**
     * Get page id from url, based on current Contao settings
     *
     * Note: In some cases this will never be called...
     *
     * @param array $arrFragments
     *
     * @return array
     */
    public function getPageIdFromUrl(Array $arrFragments)
    {
        // Check if url fragments are available (see #66)
        if (empty($arrFragments[0])) {
            return $arrFragments;
        }

        $arrFragments = array_map('urldecode', $arrFragments);
        $arrLanguages = $this->getRootPageByDomain();
        $strLanguageDefault = $arrLanguages->language;

        //global $objPage;
        //echo $strLanguageDefault = $this->Database->prepare("SELECT language FROM tl_page WHERE id=?")->execute($objPage->rootId)->language;

        // If no root pages found, return
        if (!count($arrLanguages)) {echo 'test';
            return $arrFragments;
        }

        // Get default language
        $strLanguage        = $strLanguageDefault;
        $arrMappedFragments = $this->mapUrlFragments($arrFragments);

        // try to get language by i18nl10n URL
        if (\Config::get('i18nl10n_urlParam') === 'url') {
            // First entry must be language
            $strLanguage = $arrFragments[0];
        } // try to get language by suffix
        elseif (\Config::get('i18nl10n_urlParam') === 'alias' && !\Config::get('disableAlias')) {

            $intLastIndex = count($arrFragments) - 1;
            $strRegex     = '@^([_\-\pL\pN\.]*(?=\.))?\.?([a-z]{2})$@u';

            // last element should contain language info
            if (preg_match($strRegex, $arrFragments[$intLastIndex], $matches)) {
                $strLanguage = strtolower($matches[2]);
            }
        } elseif (\Input::get('language')) {
            $strLanguage = \Input::get('language');
        }

        // try to find localized page by alias

				$strAlias = $arrMappedFragments[0];
				$strOriginAlias = $this->Database->prepare("SELECT t2.alias FROM tl_page_language t1 LEFT JOIN tl_page t2 ON t1.pid = t2.id WHERE t1.alias=? AND t1.language=?")->execute($strAlias, $strLanguage)->alias;

      	$arrAlias['alias'] = $strOriginAlias;
        $arrAlias['l10nAlias'] = $strAlias;

if(!$arrAlias['alias']) { $arrAlias['alias'] = $strAlias; }
        // Remove first entry (will be replaced by alias further on)
        array_shift($arrMappedFragments);

        // if alias has folder, remove related entries
        if (strpos($arrAlias['alias'], '/') !== false || strpos($arrAlias['l10nAlias'], '/') !== false) {
            $arrAliasFragments = array_merge(explode('/', $arrAlias['alias']), explode('/', $arrAlias['l10nAlias']));

            // remove alias parts
            foreach ($arrAliasFragments as $strAliasFragment) {
                // if alias part is still part of arrFragments, remove it from there
                if (($key = array_search($strAliasFragment, $arrMappedFragments)) !== false) {
                    $arrMappedFragments = array_delete($arrMappedFragments, $key);
                }
            }
        }

        // Insert alias
        array_unshift($arrMappedFragments, $arrAlias['alias']);

        // Add language
        // Contao doesn't like language as part of fragments, when language is a parameter
        if (\Config::get('i18nl10n_urlParam') !== 'parameter') {
            array_push($arrMappedFragments, 'language', $strLanguage);
        }

        // Add the second fragment as auto_item if the number of fragments is even
        if (\Config::get('useAutoItem') && count($arrMappedFragments) % 2 == 0) {
            array_insert($arrMappedFragments, 1, array('auto_item'));
        }

        return $arrMappedFragments;
    }


    /**
     * Get a root page by given or actual domain
     *
     * @param String    [$strDomain]    Default: null
     *
     * @return \Database\Result
     */
    public function getRootPageByDomain($strDomain = null)
    {
        if (empty($strDomain)) {
            $strDomain = \Environment::get('host');
        }

        // Find page with related or global DNS
        return \Database::getInstance()
            ->prepare('
            (SELECT * FROM tl_page WHERE type = "root" AND dns = ?)
            UNION
            (SELECT * FROM tl_page WHERE type = "root" AND dns = "")')
            ->limit(1)
            ->execute($strDomain);
    }


    /**
     * Clean url fragments from language and auto_item
     *
     * @param $arrFragments
     *
     * @return array
     */
    private function mapUrlFragments($arrFragments)
    {
        // Delete auto_item
        if (\Config::get('useAutoItem') && $arrFragments[1] === 'auto_item') {
            $arrFragments = array_delete($arrFragments, 1);
        }

        // Delete language if first part of url
        if (\Config::get('i18nl10n_urlParam') === 'url') {
            $arrFragments = array_delete($arrFragments, 0);
        } // Delete language if part of alias
        elseif (\Config::get('i18nl10n_urlParam') === 'alias' && !\Config::get('disableAlias')) {

            $lastIndex = count($arrFragments) - 1;
            $strRegex  = '@^([_\-\pL\pN\.]*(?=\.))?\.?([a-z]{2})$@u';

            // last element should contain language info
            if (preg_match($strRegex, $arrFragments[$lastIndex], $matches)) {
                $arrFragments[$lastIndex] = $matches[1];
            }
        }

        return $arrFragments;
    }

}
