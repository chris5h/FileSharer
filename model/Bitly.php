<?php
require_once 'Db.php';
class Bitly {

    private $key;
    private $group;

    function getGroupGuid(){
        $headers = ['Content-type: application/json', 'Authorization: Bearer '.bitly_token];
        $curl = curl_init('https://api-ssl.bitly.com/v4/groups');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_POST, false);
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (!in_array($status, [200,201,204,301,422])) {
            return "Error: call to Bitly failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl);
        }
        curl_close($curl);
        $r = json_decode($json_response);
        $this->group = $r->groups[0]->guid;
    }

    function shortenLink($url){
        $headers = ['Content-type: application/json', 'Authorization: Bearer '.bitly_token];
        if (is_null($this->group)){
            $this->getGroupGuid();
        }
        $data = [
            "group_guid" => $this->group,
            "domain" => "bit.ly",
            "long_url" => $url 
        ];

        $curl = curl_init("https://api-ssl.bitly.com/v4/shorten");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (!in_array($status, [200,201,204,301,422])) {
            return "Error: call to Bitly failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl);
        }
        curl_close($curl);
        return json_decode($json_response, true);
    }

    static public function getAll(){
        $conn =  Db::conn();
        $query = "SELECT * FROM viewAllBitlyLinks";
        if($stmt = mysqli_prepare($conn, $query)){
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $list[] = $row;
                }
            }
        }
        return $list;
    }

    static public function testKey($key){
        $headers = ['Content-type: application/json', 'Authorization: Bearer '.$key];
        $curl = curl_init('https://api-ssl.bitly.com/v4/groups');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_POST, false);
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status == 200){
            return true;
        }   else    {
            return false;
        }
        curl_close($curl);
    }
}

