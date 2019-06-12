<?php

use Slashworks\ContaoStarterBundle\DataContainer\General;

/**
 * Show named templates in the customTpl field.
 */
$GLOBALS['TL_DCA']['tl_form_field']['fields']['customTpl']['options_callback'] = array(General::class, 'getCustomFormFieldTemplates');


/**
 * New linebreak form field element
 */
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['linebreak'] = '{type_legend},type;{expert_legend:hide},class;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';
