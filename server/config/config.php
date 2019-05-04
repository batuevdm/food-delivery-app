<?php

Config::set('site.name', 'Food Delivery');
Config::set('site.desc', 'Доставка еды');
Config::set('site.logo', '/images/logo.png');

Config::set('contact.address', 'Россия, Удмуртская республика, г. Глазов, ул. Энгельса, 1, 2');
Config::set('contact.phone', '8-912-455-70-97');
Config::set('contact.email', 'support@batuevdm.ru');

Config::set('languages', array('ru'));

Config::set('routes', array(
    'default' => '',
    'dashboard' => 'dashboard_',
    'api' => 'api_',
));

Config::set('email.from', 'no-reply@food.batuevdm.ru');
Config::set('email.orders', 'i@batuevdm.ru');

Config::set('default.route', 'default');
Config::set('default.language', 'ru');
Config::set('default.controller', 'main');
Config::set('default.action', 'index');

// Database connection
Config::set('db.host', '127.0.0.1');
Config::set('db.user', 'food_shop');
Config::set('db.pass', '*****');
Config::set('db.base', 'food_shop');

Config::set('photo.default', 'default.png');

Config::set('products.page', 18);
Config::set('pagination.pages', 7);

Config::set('password.salt', '8k7i8k79g8d9hgf9h89#Rsg@8df9g');

Config::set('order.status', array(
    0 => 'Ожидает отправки',
    1 => 'Отправлен',
    2 => 'Доставлен',
    3 => 'Получен покупателем',
    4 => 'Отменен',
));

Config::set('storage.photo', '/images/products/');