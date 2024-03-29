<?php
include 'globals.php';

$title = 'get-users';
include 'partials/header.php';
require_once('db.php');

function main(){
    $db = new DB();
    $users = null;
    $log = array('saved'=>0,'ignored'=>0,'updated'=>0,'deleted'=>0);

    // get users list from API
    try{
        $users = curl_post('http://become.weblife.co.il/api/users.php?key=become&password=become-2019', array())->users;
    }
    catch (Exception $e){
        error_log($e);
    }
    if(!$users){ exit('Error getting users from DB');}


    // get cities mapping from DB
    $database = $db::getDbCon();
    $cities = $database->select_all("cities");


    // Find Jerusalem city_id from cities
    $jerusalem_id = $cities[array_search('Jerusalem',array_column($cities, 'city_name'))]['city_id'];

    // Insert || Update valid users
    foreach ($users as $user){
        $age = getAge($user->birth_date);

        if($age >= 18){

            if($user->city_id == $jerusalem_id && $age < 21){ $log['deleted']++; continue; } // Skip users from Jerusalem who are under 21

            try { // Insert || Update users in DB
                $database->insert_user($user);
                $database->execute();

                // Log array update
                if($database->rowCount() == 1){ $log['saved']++; }
                else if($database->rowCount() == 2){ $log['updated']++; }
                else{ $log['ignored']++; }

            } catch (PDOException $e) {
                print_r("DB Insert failed: ".$e);
            }
        }
        else{ // age < 18
            $log['deleted']++;
        }
    }

    render_results_table($log);
}

main();


include 'partials/footer.php';
/*
 *  Helper Functions
 */

/**
 * gets an assoc array and prints a formated table of the $key=>$value pair
 * @param array $results
 */
function render_results_table($results){
    $html = '<table class="table">
                <thead>
                <tr>
                  <th scope="col">Operation</th>
                  <th scope="col">Executed</th>
                </tr>
              </thead>
              <tbody>';

    foreach ($results as $key=>$value){
        $html .= '<tr>
                      <td>'.$key.'</td>
                      <td>'.$value.'</td>
                  </tr>';
    }
    $html .= '</tbody>
            </table>';

    print_r($html);
}

/**
 * @param string $date format: yyyy-mm-dd
 * @return int
 */
function getAge($date) {
    return intval(date('Y', time() - strtotime($date))) - 1970;
}

/**
 * @param $url
 * @param array $params
 * @return object $response
 * @throws Exception
 */
function curl_post($url,$params){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    if(!$response){ throw new Exception("Curl failed"); }
    curl_close ($ch);

    return json_decode($response);
}
