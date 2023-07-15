<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');


include './lib/database.php';

$db = new Database('localhost', 'root', 'root', 'cms_bandefm');

function json($data) {
    header('Content-Type: application/json');
    return json_encode($data);
}

function html($data) {
    header('Content-Type: text/html');
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new \Twig\Environment($loader, [
        'cache' => false # __DIR__.'/tmp/template_cache',
    ]);

    return $twig->render('app.twig.html', $data);
}

// Declaration of the actions:
$routes = [
    'GET /app' => [
        'data' => [
            'title' => 'Gestion de contenu',
        ]
    ],
    'GET /pages' => [
        'query' => "SELECT ID, post_title, post_name FROM wp_posts WHERE post_status = ? AND post_type = ?",
        'params' => [
            'publish',
            'page'
        ],
    ],
];


require __DIR__.'/vendor/autoload.php';


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($db, $routes) {
    
    // meta programming of some actions:
    foreach ($routes as $route => $data) {
        list($verb, $path) = explode(' ', $route);
        $r->addRoute($verb, $path, function () use ($db, $data) {
            if (isset($data['query']) && isset($data['params'])) {
                $result = $db->fetchObjects(
                    $data['query'],
                    $data['params']
                );
                echo json($result);
            } elseif (isset($data['data'])) {
                echo html($data['data']);
            } else {
                echo html(['title' => 'erreur.']);
            }
        });
    }

    // {id} must be a number (\d+)
    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});



// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        die("Erreur 404: C'pas trouvable.");
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        die("Erreur 405: Pas l'droit d'faire ça.");
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $handler($vars);
        break;
}
