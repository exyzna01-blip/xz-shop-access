<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['service'=>'netflix', 'duration'=>null, 'category'=>'solo profile', 'devices'=>'2 devices', 'price'=>130],
            ['service'=>'netflix', 'duration'=>null, 'category'=>'solo profile', 'devices'=>'1 device',  'price'=>110],
            ['service'=>'netflix', 'duration'=>null, 'category'=>'shared',       'devices'=>'',         'price'=>70],

            ['service'=>'disney',  'duration'=>null, 'category'=>'solo profile', 'devices'=>'2 devices', 'price'=>65],
            ['service'=>'disney',  'duration'=>null, 'category'=>'solo profile', 'devices'=>'1 device',  'price'=>55],
            ['service'=>'disney',  'duration'=>null, 'category'=>'shared',       'devices'=>'',          'price'=>35],

            ['service'=>'hbo max', 'duration'=>null, 'category'=>'solo profile', 'devices'=>'2 devices', 'price'=>60],
            ['service'=>'hbo max', 'duration'=>null, 'category'=>'solo profile', 'devices'=>'1 device',  'price'=>50],

            ['service'=>'viu',     'duration'=>null, 'category'=>'solo account', 'devices'=>'',          'price'=>25],
            ['service'=>'viu',     'duration'=>null, 'category'=>'shared',       'devices'=>'',          'price'=>10],

            ['service'=>'vivamax', 'duration'=>null, 'category'=>'solo account', 'devices'=>'',          'price'=>110],
            ['service'=>'vivamax', 'duration'=>null, 'category'=>'shared',       'devices'=>'',          'price'=>35],

            ['service'=>'vivaone', 'duration'=>null, 'category'=>'solo account', 'devices'=>'',          'price'=>110],
            ['service'=>'vivaone', 'duration'=>null, 'category'=>'shared',       'devices'=>'',          'price'=>35],

            ['service'=>'prime video','duration'=>null,'category'=>'solo account','devices'=>'',          'price'=>30],
            ['service'=>'prime video','duration'=>null,'category'=>'solo profile','devices'=>'2 dev',     'price'=>10],
            ['service'=>'prime video','duration'=>null,'category'=>'shared',      'devices'=>'',          'price'=>5],

            ['service'=>'loklok basic','duration'=>null,'category'=>'shared',     'devices'=>'',          'price'=>35],

            ['service'=>'youtube famhead','duration'=>null,'category'=>'famhead', 'devices'=>'',          'price'=>45],
            ['service'=>'youtube individual','duration'=>null,'category'=>'individual','devices'=>'',     'price'=>35],
            ['service'=>'youtube invite','duration'=>null,'category'=>'invite',   'devices'=>'',          'price'=>7],

            ['service'=>'crunchyroll','duration'=>null,'category'=>'shared',      'devices'=>'',          'price'=>35],

            ['service'=>'quizlet','duration'=>null,'category'=>'solo account',    'devices'=>'',          'price'=>25],
            ['service'=>'quizlet','duration'=>null,'category'=>'shared',          'devices'=>'',          'price'=>15],

            ['service'=>'quillbot','duration'=>null,'category'=>'solo account',   'devices'=>'',          'price'=>55],
            ['service'=>'quillbot','duration'=>null,'category'=>'shared',         'devices'=>'',          'price'=>15],

            ['service'=>'grammarly','duration'=>null,'category'=>'solo account',  'devices'=>'',          'price'=>35],
            ['service'=>'grammarly','duration'=>null,'category'=>'shared',        'devices'=>'',          'price'=>10],

            ['service'=>'scribd','duration'=>null,'category'=>'solo account',     'devices'=>'',          'price'=>20],
            ['service'=>'scribd','duration'=>null,'category'=>'shared',           'devices'=>'',          'price'=>10],

            ['service'=>'studocu','duration'=>null,'category'=>'solo account',    'devices'=>'',          'price'=>20],
            ['service'=>'studocu','duration'=>null,'category'=>'shared',          'devices'=>'',          'price'=>10],

            ['service'=>'chatgpt','duration'=>null,'category'=>'team',            'devices'=>'',          'price'=>150],
            ['service'=>'chatgpt','duration'=>null,'category'=>'shared',          'devices'=>'',          'price'=>35],
            ['service'=>'chatgpt invite','duration'=>null,'category'=>'invite',   'devices'=>'',          'price'=>65],

            ['service'=>'ms365 famhead','duration'=>null,'category'=>'famhead',   'devices'=>'',          'price'=>25],
            ['service'=>'ms365 invite','duration'=>null,'category'=>'invite',     'devices'=>'',          'price'=>7],

            ['service'=>'canva solo','duration'=>null,'category'=>'solo',         'devices'=>'',          'price'=>5],
            ['service'=>'canva invite','duration'=>null,'category'=>'invite',     'devices'=>'',          'price'=>1],
            ['service'=>'canva edu','duration'=>null,'category'=>'individual',    'devices'=>'',          'price'=>12],
            ['service'=>'canva invite','duration'=>null,'category'=>'invite',     'devices'=>'alt',       'price'=>15],

            ['service'=>'capcut solo','duration'=>null,'category'=>'solo',        'devices'=>'',          'price'=>15],
            ['service'=>'capcut shared','duration'=>null,'category'=>'shared',    'devices'=>'',          'price'=>7],
            ['service'=>'capcut solo','duration'=>null,'category'=>'solo',        'devices'=>'alt',       'price'=>40],
            ['service'=>'capcut shared','duration'=>null,'category'=>'shared',    'devices'=>'alt',       'price'=>20],
        ];

        foreach ($items as $it) {
            DB::table('price_catalog_items')->updateOrInsert(
                ['service'=>$it['service'],'duration'=>$it['duration'],'category'=>$it['category'],'devices'=>$it['devices']],
                ['price'=>$it['price'],'active'=>1,'created_at'=>now(),'updated_at'=>now()]
            );
        }
    }
}
