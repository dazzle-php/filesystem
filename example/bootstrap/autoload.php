<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$driver  = 'Dazzle\\Filesystem\\Driver\\Driver%class%';
$invoker = 'Dazzle\\Filesystem\\Invoker\\Invoker%class%';

$options = [
    'driver'  => 'DriverStandard',
    'invoker' => 'InvokerStandard',
];

foreach ($argv as $argIndex=>$argVal)
{
    if ($argIndex && preg_match('#^--([^=]+)=(.+)$#si', $argVal, $matches) && $matches)
    {
        $options[$matches[1]] = $matches[2];
    }
}

foreach ($options as $optionKey=>$optionVal)
{
    $$optionKey = preg_replace_callback(
        '#%class%#si',
        function() use($optionVal) {
            return ucwords(str_replace([ 'driver', 'invoker' ], [ '', '' ], strtolower($optionVal)));
        },
        $$optionKey
    );
}
