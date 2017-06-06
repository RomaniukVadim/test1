<?php

require_once dirname(dirname(__FILE__)) . '/lib/Predis/Autoloader.php';

Predis\Autoloader::register();

class PageNotice {
    
    
    
    public function __construct() {
        
    }

    public static function create($id, $title, $created_by, $content = array(), $channel = "csdportalnotice") {
        $WS = (getenv('HTTP_HOST') == 'psbcal.12csd.com' or getenv('HTTP_HOST') == 'psbcal.zzs33.com') ? "global" : "globaltest"; //cannot find the $WS on __construct
        try {
        $client = new Predis\Client(array(
            'host' => '122.53.154.202',
            'port' => '6379'
        ));
        }
        catch (Exception $e) {
            die($e->getMessage());
        }

        $time = time();

        $client->sadd($WS . '_' . $channel."_".$id, "0:t:$time", "0:s:$title", "u:$created_by");

        $client->publish($WS . '_' . $channel, json_encode(array('id' => $id, 'subject' => $title, 'time' => $time, 'content' => $content)));
    }

    public static function update($id, $title, $created_by, $content = array('text_content' => '', 'attachment' => array('File' => '', 'OriginalFile' => '')), $channel = "csdportalnotice") {
        $WS = getenv('HTTP_HOST') == 'psbcal.12csd.com' ? "global" : "globaltest";
        try {
        $client = new Predis\Client(array(
            'host' => '122.53.154.202',
            'port' => '6379'
        ));
        }
        catch (Exception $e) {
            die($e->getMessage());
        }

        $time = time();
        
        $client->del($WS . '_' . $channel."_".$id , $channel);

        $client->sadd($WS . '_' . $channel."_".$id, "0:t:$time", "0:s:$title", "u:$created_by");

        $client->publish($WS . '_' . $channel."_".$id, json_encode(array('id' => $id, 'subject' => $title, 'time' => $time, 'content' => $content)));
    }

    public static function read($id, $read_by, $channel = "csdportalnotice") {
        $WS = getenv('HTTP_HOST') == 'psbcal.12csd.com' ? "global" : "globaltest";
        try {
        $client = new Predis\Client(array(
            'host' => '122.53.154.202',
            'port' => '6379'
        ));
        }
        catch (Exception $e) {
            die($e->getMessage());
        }

        //    if ($client->exists(getenv('HTTP_HOST') == '10.120.10.92' ? "global{$channel}_$id" : "globaltest{$channel}_$id")) {

        $client->sadd($WS . '_' . $channel."_".$id, "u:$read_by");
        
        $client->publish($WS . '_' . $channel."_read_".$read_by, json_encode(array('id' => $id)));
        // }
        //   echo $client->publish(getenv('HTTP_HOST') == '10.120.10.92' ? "global" . $channel . "_read_$read_by" : "globaltest" . $channel . "_read_$read_by", json_encode(array('id' => $id)));
    }

    public static function remove($id, $channel = "csdportalnotice") {
        $WS = getenv('HTTP_HOST') == 'psbcal.12csd.com' ? "global" : "globaltest";
        try {
        $client = new Predis\Client(array(
            'host' => '122.53.154.202',
            'port' => '6379'
        ));
        }
        catch (Exception $e) {
            die($e->getMessage());
        }

        $client->del($WS . '_' . $channel."_".$id);

        $client->publish($WS . '_' . $channel."_remove", json_encode(array('id' => $id)));
    }

    public static function publish($channel, $data) {
        
        $WS = (getenv('HTTP_HOST') == 'psbcal.12csd.com' or getenv('HTTP_HOST') == 'psbcal.zzs33.com') ? "global" : "globaltest"; //cannot find the $WS on __construct
        try {
            $client = new Predis\Client(array(
                "scheme" => "tcp",
                "host" => "122.53.154.202",
                "port" => 6379));

            
        }
        catch (Exception $e) {
            echo "Couldn't connected to Redis";
            echo $e->getMessage();
            exit();
        }        
        $client->publish($WS . '_' . $channel, json_encode($data));


    }    
    
    
    
}
