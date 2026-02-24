<?php

return [
    'app_name' => 'xz shop access',

    'services' => [
        'netflix','disney','hbo max','viu','prime video','vivaone','vivamax','loklok basic','loklok standard',
        'youtube individual','youtube famhead','crunchyroll','iwanttfc','grammarly','quizlet','quillbot','scribd',
        'studocu','chatgpt invite','chatgpt plus','ms365 famhead','ms365 individual','canva solo','canva invite',
        'canva edu','capcut solo','capcut teamhead','picsart solo','picsart invite','spotify individual','spotify fam',
        'spotify links','gemini AI 2TB invite','gemini AI shared',
    ],

    'durations' => [
        '7 days','14 days','21 days','28 days','35 days',
        '1 month','2 months','3 months','4 months','5 months','6 months','7 months','8 months','9 months',
        '10 months','11 months','1 year','lifetime',
    ],

    'labels' => [
        'premium plan','ultimate plan','standard plan','basic plan','max plan','pro plan','plus plan','pro plan 2TB','200TB',
    ],

    'categories' => [
        'solo profile','solo','shared','solo account','invite','team','famhead','individual',
    ],

    'receipt' => [
        'max_images' => 5,
        'total_limit_bytes' => 700 * 1024 * 1024, // 700MB
    ],
];
