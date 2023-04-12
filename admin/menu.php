<?php
$aMenu[] = [
    'parent_menu' => 'global_menu_settings',
    'sort' => 3000,
    'text' => 'MAIL DUMP',
    'title' => 'MAIL DUMP',
    'icon' => 'sale_menu_icon_catalog',
    'page_icon' => 'sale_menu_icon_catalog',
    'items_id' => 'menu_maildumper',
    'items' => [
        [
            'text' => 'Список сообщений',
            'url' => 'maildumper.php?lang='.LANGUAGE_ID,
            'more_url' => [],
            'title' => 'Список сообщений',
        ],
    ],
];

return $aMenu;
