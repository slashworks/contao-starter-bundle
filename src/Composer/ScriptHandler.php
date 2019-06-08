<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Slashworks\ContaoStarterBundle\Composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Sets up the Slashworks Starter Bundle.
 *
 * @author Stefan Gruna
 */
class ScriptHandler
{
    /**
     * Runs all Composer tasks to initialize a Contao Managed Edition.
     *
     * @param Event $event
     */
    public static function initializeApplication(Event $event)
    {
        static::addAppBundleDirectory();
    }

    public static function addAppBundleDirectory()
    {
        $filesystem = new Filesystem();
        if (!$filesystem->exists(getcwd() . '/src')) {
            $source = getcwd() . '/vendor/slashworks/contao-starter-bundle/data/src';
            $target = getcwd() . '/src';

            $filesystem->mirror($source, $target);
        }
    }

}
