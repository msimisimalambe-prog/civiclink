<?php
declare(strict_types=1);

/*
 * Central service catalogue.
 * Add or edit categories and service destinations here; the pages render this data.
 */
return [
    'identity' => [
        'title' => 'Identity services',
        'description' => 'Manage identity checks and review your verification status.',
        'intro' => 'Submit your national ID details for a local demonstration check or manual review.',
        'services' => [
            [
                'title' => 'Identity verification',
                'description' => 'Submit your national ID number and view the current verification mode.',
                'url' => 'profile.php#identity',
                'action' => 'Open identity verification',
            ],
        ],
    ],
    'banking' => [
        'title' => 'Banking services',
        'description' => 'Create and view demonstration bank account records.',
        'intro' => 'Create a local demonstration account and view accounts linked to your profile.',
        'services' => [
            [
                'title' => 'Create a bank account',
                'description' => 'Create a savings or current account record for your CivicLink profile.',
                'url' => 'profile.php#banking',
                'action' => 'Manage bank accounts',
            ],
            [
                'title' => 'View your accounts',
                'description' => 'See the account records linked to your profile.',
                'url' => 'dashboard.php#accounts',
                'action' => 'View accounts',
            ],
        ],
    ],
    'home-affairs' => [
        'title' => 'Home Affairs services',
        'description' => 'Apply for and track passport, certificate and national ID services.',
        'intro' => 'Choose a service below. Sign in is required before submitting an application.',
        'services' => [
            ['title' => 'Passport application', 'description' => 'Apply for a passport.', 'url' => 'apply.php?service=Passport%20application', 'action' => 'Apply now'],
            ['title' => 'Passport renewal', 'description' => 'Request renewal of a passport.', 'url' => 'apply.php?service=Passport%20renewal', 'action' => 'Apply now'],
            ['title' => 'Passport replacement', 'description' => 'Request a replacement passport.', 'url' => 'apply.php?service=Passport%20replacement', 'action' => 'Apply now'],
            ['title' => 'Birth certificate', 'description' => 'Submit a birth certificate application.', 'url' => 'apply.php?service=Birth%20certificate', 'action' => 'Apply now'],
            ['title' => 'Marriage certificate', 'description' => 'Submit a marriage certificate application.', 'url' => 'apply.php?service=Marriage%20certificate', 'action' => 'Apply now'],
            ['title' => 'Death certificate', 'description' => 'Submit a death certificate application.', 'url' => 'apply.php?service=Death%20certificate', 'action' => 'Apply now'],
            ['title' => 'National ID replacement', 'description' => 'Request replacement of a national ID.', 'url' => 'apply.php?service=National%20ID%20replacement', 'action' => 'Apply now'],
            ['title' => 'Track an application', 'description' => 'Check progress using your application reference.', 'url' => 'track.php', 'action' => 'Track application'],
        ],
    ],
];
