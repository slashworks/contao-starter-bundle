<?php

use Slashworks\ContaoStarterBundle\DataContainer\General;

/**
 * Show named templates in the customTpl field.
 */
$GLOBALS['TL_DCA']['tl_form_field']['fields']['customTpl']['options_callback'] = array(General::class, 'getCustomFormFieldTemplates');
