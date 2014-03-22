<?php

    include_once join('/', array(__DIR__, 'AutoLoader.php'));
    AutoLoader::registerDirectory(join('/', array(__DIR__, '..', 'src' )));

    require_once __DIR__ . '/../src/RESTful/RESTfulInterface.php';
    require_once __DIR__ . '/../src/RESTful/RESTfulStub.php';
