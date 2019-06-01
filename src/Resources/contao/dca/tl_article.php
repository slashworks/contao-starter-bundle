<?php

use Slashworks\ContaoStarterBundle\DataContainer\General;

/**
 * Show named templates in the customTpl field.
 */
$GLOBALS['TL_DCA']['tl_article']['fields']['customTpl']['options_callback'] = array(General::class, 'getCustomArticleTemplates');
