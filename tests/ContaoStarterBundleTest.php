<?php

/*
 * This file is part of [package name].
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
 */

namespace Slashworks\ContaoStarterBundle\Tests;

use Slashworks\ContaoStarterBundle\ContaoStarterBundle;
use PHPUnit\Framework\TestCase;

class ContaoStarterBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new ContaoStarterBundle();

        $this->assertInstanceOf('Slashworks\ContaoStarterBundle\ContaoStarterBundle', $bundle);
    }
}
