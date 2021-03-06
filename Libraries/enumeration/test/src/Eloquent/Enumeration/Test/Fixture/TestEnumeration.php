<?php

/*
 * This file is part of the Enumeration package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Enumeration\Test\Fixture;

use Eloquent\Enumeration\Enumeration;

abstract class TestEnumeration extends Enumeration
{
    const FOO = 'oof';
    const BAR = 'rab';
}
