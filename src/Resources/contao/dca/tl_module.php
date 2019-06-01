<?php

use Slashworks\ContaoStarterBundle\DataContainer\General;

/**
 * Show named templates in the all custom template realted fields.
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['customTpl']['options_callback'] = array(General::class, 'getCustomModuleTemplates');
$GLOBALS['TL_DCA']['tl_module']['fields']['navigationTpl']['options_callback'] = array(General::class, 'getCustomNavigationTemplates');
$GLOBALS['TL_DCA']['tl_module']['fields']['memberTpl']['options_callback'] = array(General::class, 'getCustomMemberTemplates');
$GLOBALS['TL_DCA']['tl_module']['fields']['searchTpl']['options_callback'] = array(General::class, 'getCustomSearchTemplates');
$GLOBALS['TL_DCA']['tl_module']['fields']['rss_template']['options_callback'] = array(General::class, 'getCustomRssTemplates');
