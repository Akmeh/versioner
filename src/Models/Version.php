<?php
declare(strict_types=1);

namespace Versioning\Models;

class Version
{
    const FIRST_VERSION = '0.0.0';
    const VERSION_FILE = '.version';
    const SEVERITY = [
        'major' => 0,
        'minor' => 1,
        'patch' => 2,
    ];

    const COMMIT_STRUCTURE = [
        'fix' => 'patch',
        'feat' => 'minor',
        'breaking change' => 'major',
        'hotfix' => 'patch',
        'refactor' => 'patch',
        'story' => 'minor',
    ];


    const FLAG_USER = 'Rendy Eko Prastiyo';
}
