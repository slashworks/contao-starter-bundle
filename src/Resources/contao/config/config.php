<?php

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['contaostarter'] = array
(
    'callback' => Slashworks\ContaoStarterBundle\Backend\ContaoStarterInstall::class,
);

array_insert($GLOBALS['BE_MOD']['content'], 1, array
(
    'news' => array
    (
        'tables'      => array('tl_news_archive', 'tl_news', 'tl_news_feed', 'tl_content'),
        'table'       => array('TableWizard', 'importTable'),
        'list'        => array('ListWizard', 'importList')
    )
));
