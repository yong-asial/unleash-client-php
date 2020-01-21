<?php
require('vendor/autoload.php');

use Minds\UnleashClient\Config;
use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Logger;
use Minds\UnleashClient\Unleash;

function main() : void
{
    $logger = new Logger();
    $logger->debug('Unleash client demo');

    $config = new Config(
        "https://gitlab.com/api/v4/feature_flags/unleash/14894840/",
        "F2qZp9PyWKXDas9mkEsH",
        "test",
        300,
        15
    );

    $context = new Context();
    $context
        ->setUserId('1000')
        ->setUserGroups(['plus', 'pro'])
        ->setSessionId('asdasdqweqwe123123')
        ->setRemoteAddress('127.0.0.1')
        ->setHostName('www.minds.com');

    $unleash = new Unleash($config, $logger);
    $unleash
        ->setContext($context);

    $enabled = $unleash->isEnabled('test', false);
    $logger->info("'test' flag evaluates to {$enabled}");

    $enabled = $unleash->isEnabled('test-fiftypercent', false);
    $logger->info("'test-fiftypercent' flag evaluates to {$enabled}");

    $enabled = $unleash->isEnabled('test-selective', false);
    $logger->info("'test-selective' flag evaluates to {$enabled}");

    $enabled = $unleash->isEnabled('test_on_test', false);
    $logger->info("'test_on_test' flag evaluates to {$enabled}");

    $enabled = $unleash->isEnabled('non_existing_flag', false);
    $logger->info("'non_existing_flag' flag evaluates to {$enabled}");

    $enabled = $unleash->isEnabled('test_group_admin', false);
    $logger->info("'test_group_admin' flag evaluates to {$enabled}");

    $enabled = $unleash->isEnabled('test_group_pro', false);
    $logger->info("'test_group_pro' flag evaluates to {$enabled}");

    print_r($unleash->export());
}

main();
