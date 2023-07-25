<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');


include './lib/database.php';
include './lib/route.php';
require __DIR__.'/vendor/autoload.php';

$db = new Database('localhost', 'root', 'root', 'cms_bandefm');

function handle_route($routes, $current_uri) {
    foreach ($routes as $route_str => $thing) {
        list($http_verb, $location) = explode(' ', $route_str);
        $route = new Route($http_verb, $location);
        if ($route->matches($current_uri)) {
            // depending on $thing's nature, do something to handle this route...
            if (is_array($thing) && isset($thing['query']) && isset($thing['params'])) {
                $data = get_data($thing);
                echo json(['data' => $data]);
                exit;
            }

            if (is_callable($thing)) {
                $thing($_GET, $_POST);
                exit;
            }
        }
    }
}

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

/**
 * @param array $params [query] => String, [params] => Array
 */
function get_data($params) {
    global $db;
    return $db->fetchObjects($params['query'], $params['params']);
}



// Declaration of the actions:
$routes = [
    'GET /pages' => [
        'query' => "SELECT ID, post_title, post_name FROM wp_posts WHERE post_status = ? AND post_type = ?",
        'params' => [
            'publish',
            'page'
        ],
    ],
    'GET /pages/:id' => [

    ],
];


// Allow requests from http://localhost:8080
header('Access-Control-Allow-Origin: http://localhost:8080');

// Set allowed request methods (e.g., GET, POST, OPTIONS)
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Allow specific request headers
header('Access-Control-Allow-Headers: Content-Type');

// Allow credentials (if your frontend sends cookies with requests)
header('Access-Control-Allow-Credentials: true');

// Handle preflight requests for non-simple methods (e.g., POST with custom headers)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No content in response for preflight requests
    exit();
}

$current_uri = $_GET['uri'];

handle_route($routes, $current_uri);
