<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList();
        $router->addRoute('/api/v<version>/<package>[/<apiAction>][/<params>]', 'Api:Api:default');

        return $router;
    }
}
