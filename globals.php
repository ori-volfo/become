<?php
$project_path = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'], 2)[0].'/../';

$scripts['stats'][] = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0-rc.1/Chart.bundle.min.js';
$scripts['stats'][] = $project_path.'scripts/utils.js';
$scripts['stats'][] = $project_path.'scripts/stats.js';

$scripts['users'][] = 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js';
$scripts['users'][] = $project_path.'scripts/utils.js';
$scripts['users'][] = $project_path.'scripts/users.js';


$scripts['get-users'][] = '';