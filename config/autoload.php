<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'SwitchLanguage',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'SwitchLanguage\FrontendHook'         => 'system/modules/switch_language/classes/FrontendHook.php',

	// Modules
	'SwitchLanguage\ModulePageExt'        => 'system/modules/switch_language/modules/ModulePageExt.php',
	'SwitchLanguage\ModuleContentExt'     => 'system/modules/switch_language/modules/ModuleContentExt.php',
	'SwitchLanguage\ModuleSwitchLanguage' => 'system/modules/switch_language/modules/ModuleSwitchLanguage.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_switch_language' => 'system/modules/switch_language/templates',
));
