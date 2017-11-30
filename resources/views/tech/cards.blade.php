<transition-group appear tag="div" class="card-columns" name="list" mode="out-in">
    @foreach([
        __('global/tech.serverSide') =>[[
                'dark' => true,
                'title' => 'PHP 7',
                'image' => 'php.png',
                'url' => 'https://php.net',
                'description' => __('global/tech.php')
            ],[
                'title' => 'MySQL',
                'image' => 'mysql.png',
                'url' => 'https://laravel.com',
                'description' => __('global/tech.mysql')
            ],[
                'dark' => 'true',
                'title' => 'Laravel',
                'image' => 'laravel.png',
                'url' => 'https://mysql.com',
                'description' => __('global/tech.laravel')
            ],[
                'title' => 'Redis',
                'image' => 'redis.png',
                'url' => 'https://redis.io',
                'description' => __('global/tech.redis')
            ],[
                'dark' => true,
                'title' => 'Nginx',
                'image' => 'nginx.png',
                'url' => 'https://nginx.org',
                'description' => __('global/tech.nginx')
            ]],
        __('global/tech.clientSide') => [[
                'dark' => true,
                'title' => 'Vue.js',
                'image' => 'vue.png',
                'url' => 'https://vuejs.org',
                'description' => __('global/tech.vue')
            ],[
                'image' => 'jquery.png',
                'title' => "jQuery",
                'url' => 'https://jquery.com',
                'description' => __('global/tech.jquery')
            ],[
                'dark' => true,
                'image' => '',
                'title' => "Axios",
                'url' => 'https://github.com/axios/axios',
                'description' => __('global/tech.axios')
            ],[
                'image' => 'leaflet.png',
                'title' => "Leaflet",
                'url' => 'http://leafletjs.com/',
                'description' => __('global/tech.leaflet')
            ]],
        __('global/tech.design') => [[
                'image' => 'sass.svg',
                'title' => 'Sass',
                'url' => 'https://sass-lang.com',
                'description' => __('global/tech.sass')
            ],[
                'dark' => true,
                'image' => 'fontawesome.jpg',
                'title' => 'Font Awesome',
                'url' => 'https://fontawesome.io',
                'description' => __('global/tech.fontAwesome')
            ],[
                'image' => 'bootstrap.png',
                'title' => 'Bootstrap',
                'url' => 'https://getbootstrap.com',
                'description' => __('global/tech.bootstrap')
            ]],
        __('global/tech.testing') => [[
                'dark' => true,
                'image' => 'phpunit.png',
                'title' => 'PHPUnit',
                'url' => 'https://phpunit.de',
                'description' => __('global/tech.phpunit')
            ],[
                'image' => 'dusk.svg',
                'title' => 'Laravel Dusk',
                'url' => 'https://github.com/laravel/dusk',
                'description' => __('global/tech.dusk')
            ],[
                'dark' => true,
                'image' => '',
                'title' => 'fzaninotto Faker',
                'url' => 'https://github.com/fzaninotto/Faker',
                'description' => __('global/tech.faker')
            ],
        ],
        __('global/tech.development') => [[
                'dark' => true,
                'image' => 'node.svg',
                'title' => 'Node.js',
                'url' => 'https://nodejs.org',
                'description' => __('global/tech.nodejs')
            ],[
                'image' => 'laravel-mix.svg',
                'title' => 'Laravel Mix',
                'url' => 'https://github.com/JeffreyWay/laravel-mix',
                'description' => __('global/tech.mix')
            ],[
                'dark' => true,
                'image' => 'webpack.svg',
                'title' => 'Webpack',
                'url' => 'https://webpack.js.org',
                'description' => __('global/tech.webpack')
            ],[
                'image' => 'laravel-debugbar.png',
                'title' => 'Laravel Debugbar',
                'url' => 'https://github.com/barryvdh/laravel-debugbar',
                'description' => __('global/tech.debugbar')
            ],[
                'image' => '',
                'title' => 'Laravel IDE Helper',
                'url' => 'https://github.com/barryvdh/laravel-ide-helper',
                'description' => __('global/tech.ideHelper')
            ],
        ]
    ] as $category => $cards)
        @foreach($cards as $card)
            @component('tech.components.card', array_merge($card,['category' => $category])))
            @endcomponent
        @endforeach
    @endforeach
</transition-group>
