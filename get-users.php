<?php

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
    $database->query("SELECT * FROM cities");
    $cities = $database->resultSet();

    // Find Jerusalem city_id from cities
    $jerusalem_id = $cities[array_search('Jerusalem',array_column($cities, 'city_name'))]['city_id'];

    // Insert || Update valid users
    foreach ($users as $user){
        $age = getAge($user->birth_date);

        if($age >= 18){

            if($user->city_id == $jerusalem_id && $age < 21){ $log['deleted']++; continue; } // Skip users from Jerusalem who are under 21

            try { // Insert || Update users in DB
                $database = $db::getDbCon();
                $database->query("INSERT INTO `users` (first_name,last_name,email,birth_date,phone,city_id) 
                                        VALUES (:first_name,:last_name,:email,:birth_date,:phone,:city_id)
                                        ON DUPLICATE KEY UPDATE 
                                            `first_name` = :first_name,
                                            `last_name` = :last_name,
                                            `birth_date` = :birth_date,
                                            `phone` = :phone,
                                            `city_id` = :city_id;");
                $database->bind(':first_name', $user->first_name);
                $database->bind(':last_name', $user->last_name);
                $database->bind(':email', $user->email);
                $database->bind(':birth_date', $user->birth_date);
                $database->bind(':phone', $user->phone);
                $database->bind(':city_id', $user->city_id);
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

    echo '<pre>';
    print_r($log);
    echo '</pre>';
}

main();


/*
 *  Helper Functions
 */


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
