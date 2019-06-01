<?php

/**
 * Show named templates in the customTpl field.
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['navigationTpl']['options_callback'] = array(\Slashworks\ContaoStarterBundle\DataContainer\General::class, 'getCustomTemplateNames');
$GLOBALS['TL_DCA']['tl_module']['fields']['customTpl']['options_callback'] = array(\Slashworks\ContaoStarterBundle\DataContainer\General::class, 'getCustomTemplateNames');
