<?php
declare(strict_types=1);

namespace Versioning\Models;

class Version
{
    const FIRST_VERSION = '0.0.1';
    const VERSION_FILE = '.version';
    const SEVERITY = [
        'major' => 0,
        'minor' => 1,
        'patch' => 2,
    ];
}
