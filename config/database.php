<?php
return [
    'host'     => getenv('DB_HOST'),
    'dbname'   => getenv('DB_NAME'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'port'     => getenv('DB_PORT') ?: '5432',
];
