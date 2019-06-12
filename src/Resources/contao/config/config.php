<?php

use Slashworks\ContaoStarterBundle\Backend\ContaoStarterInstall;
use Slashworks\ContaoStarterBundle\ContentElement\FlickityStart;
use Slashworks\ContaoStarterBundle\ContentElement\FlickityStop;
use Slashworks\ContaoStarterBundle\Form\LineBreak;
use Slashworks\ContaoStarterBundle\Hook\LoadFormField;

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['contaostarter'] = array
(
    'callback' => ContaoStarterInstall::class,
);


/**
 * Front end form fields
 */
$GLOBALS['TL_FFL']['linebreak'] = LineBreak::class;


/**
 * Content elements
 */
$GLOBALS['TL_CTE']['slider']['flickitystart'] = FlickityStart::class;
$GLOBALS['TL_CTE']['slider']['flickitystop'] = FlickityStop::class;


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadFormField'][] = array(LoadFormField::class, 'addWidgetNames');


/**
 * Wrapper elements
 */
$GLOBALS['TL_WRAPPERS']['start'][] = 'flickitystart';
$GLOBALS['TL_WRAPPERS']['stop'][] = 'flickitystop';


/**
 * Front end assets
 */
if (TL_MODE === 'FE') {
    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaostarter/vendor/object-fit-images/ofi.min.js';
}
