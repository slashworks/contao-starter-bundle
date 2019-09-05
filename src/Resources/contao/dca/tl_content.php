<?php

use Slashworks\ContaoStarterBundle\DataContainer\General;
use Slashworks\ContaoStarterBundle\DataContainer\Content;

/**
 * Show named templates in the customTpl field.
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['customTpl']['options_callback'] = array(General::class, 'getCustomElementTemplates');


/**
 * Flickity start and stop elements
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['flickitystart'] = '{type_legend},type;{flickity_legend},flickityConfiguration,exampleFlickityConfiguration;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['flickitystop'] = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['flickityConfiguration'] = array
(
    'label'       => &$GLOBALS['TL_LANG']['tl_content']['flickityConfiguration'],
    'exclude'     => true,
    'inputType'   => 'textarea',
    'eval'        => array('preserveTags' => true, 'decodeEntities' => true, 'class' => 'monospace', 'rte' => 'ace|js', 'helpwizard' => true),
    'explanation' => 'insertTags',
    'sql'         => "text NULL",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['exampleFlickityConfiguration'] = array
(
    'label'       => &$GLOBALS['TL_LANG']['tl_content']['exampleFlickityConfiguration'],
    'value'       => '123',
    'inputType'   => 'textarea',
    'load_callback' => array
    (
        array(Content::class, 'generateExampleFlickityConfiguration'),
    ),
    'eval' => array('class' => 'monospace', 'disabled' => true, 'readonly' => true),
    'explanation' => 'insertTags',
);
