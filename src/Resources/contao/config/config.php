<?php

use Slashworks\ContaoStarterBundle\Backend\ContaoStarterInstall;
use Slashworks\ContaoStarterBundle\Hook\LoadFormField;

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['contaostarter'] = array
(
    'callback' => ContaoStarterInstall::class,
);


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadFormField'][] = array(LoadFormField::class, 'addWidgetNames');


/**
 * Front end assets
 */
if (TL_MODE === 'FE') {
    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaostarter/vendor/object-fit-images/ofi.min.js';
}
