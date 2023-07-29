<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');


include './lib/database.php';
include './lib/route.php';
require __DIR__.'/vendor/autoload.php';

$db = new Database('localhost', 'root', 'root', 'cms_bandefm');

function error_404() {
    http_response_code(404);
    echo '404 not found';
    exit;
}

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
                $params = $route->parse($current_uri);
                $thing(...$params);
                exit;
            }
        }
    }

    error_404();
}

function json($data) {
    header('Content-Type: application/json');
    return json_encode($data);
}

/**
 * @param array $params [query] => String, [params] => Array
 */
function get_data($params) {
    global $db;
    return $db->fetchObjects($params['query'], $params['params']);
}


spl_autoload_register(function ($class_name) {
	// Convert the class name to filename format
	$file_name = convertClassNameToFileName($class_name);
	// try in actions/:
	$file_path = __DIR__.'/actions/'.$file_name;
	if (file_exists($file_path)) {
			require_once($file_path);
	}
	// try again, in lib/:
	$file_path = __DIR__.'/lib/'.$file_name;
	if (file_exists($file_path)) {
			require_once($file_path);
	}
});

function convertClassNameToFileName($class_name) {
    // Make the first letter lowercase
    $file_name = lcfirst($class_name);

    // Add underscores before each uppercase letter
    $file_name = preg_replace('/([a-z])([A-Z])/', '$1_$2', $file_name);

    // Add the "_class.php" extension
    $file_name .= '.php';

    return strtolower($file_name);
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
    'GET /pages/:id/edit' => function ($id) {
      echo new PageUpsert($id);
    },
    'GET /pages/:id' => function ($id) {
      echo 'joie';
    },
];


# // Allow requests from http://localhost:8080
# header('Access-Control-Allow-Origin: http://localhost:8080');
# 
# // Set allowed request methods (e.g., GET, POST, OPTIONS)
# header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
# 
# // Allow specific request headers
# header('Access-Control-Allow-Headers: Content-Type');
# 
# // Allow credentials (if your frontend sends cookies with requests)
# header('Access-Control-Allow-Credentials: true');
# 
# // Handle preflight requests for non-simple methods (e.g., POST with custom headers)
# if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
#     http_response_code(204); // No content in response for preflight requests
#     exit();
# }
# 

$current_uri = $_GET['uri'];
 
handle_route($routes, $current_uri);

