<?php

namespace Controllers;

use Dinusha\Mpd\MpdClient;

class Player {
    private $mpdClient;
    
    public function __construct() {
        $this->mpdClient = new MpdClient('192.168.0.6', '6600');
        $this->mpdClient->connect();
    }
    
    public function index(){   

        //$client = new MpdClient('192.168.0.5', '6600');
        //$this->mpdClient->connect();
        //$resp = $this->mpdClient->sendCommand(MpdClient::STATUS);
        //echo json_encode($resp);
        getView('player');
    }
    
    public function play(){
        $this->mpdClient->sendCommand(MpdClient::PLAY);
    }
    
    public function next(){
        $this->mpdClient->sendCommand(MpdClient::NEXT);
    }
    
    public function prev(){
        $this->mpdClient->sendCommand(MpdClient::PREVIOUS);
    }
    
    public function pause(){
        $this->mpdClient->sendCommand(MpdClient::PAUSE);
    }
    
    public function stop(){
        $this->mpdClient->sendCommand(MpdClient::STOP);
    }
    
    public function status(){
        $resp = $this->mpdClient->sendCommand(MpdClient::STATUS);
        echo json_encode($resp);
    }
    
    public function queue(){
        $files = [];
        $resp = $this->mpdClient->sendCommand('playlistinfo');
        foreach ($resp as $value) {
            if (array_key_exists('file', $value))
                array_push($files, $value['file']);
        }
        echo json_encode($files);
    }
}
