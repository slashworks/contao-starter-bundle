<?php

/**
 * Show named templates in the customTpl field.
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['customTpl']['options_callback'] = array(\Slashworks\ContaoStarterBundle\DataContainer\General::class, 'getCustomTemplateNames');
