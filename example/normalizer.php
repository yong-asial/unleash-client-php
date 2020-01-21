<?php
require('vendor/autoload.php');

use Minds\UnleashClient\Helpers\NormalizedValue;
use Minds\UnleashClient\Logger;

function main()
{
    $logger = new Logger();
    $logger->debug('Unleash normalizer stats');

    $normalizedValue = new NormalizedValue();
    $stats = array_fill(1, 100, 0);

    $min = 1;
    $max = 999999;
    $groupId = 'default';

    for ($i = $min; $i <= $max; $i++) {
        $id = $normalizedValue->build("$i", $groupId, 100, 1);
        $stats[(int) $id]++;
    }

    var_export($stats);
}

main();
