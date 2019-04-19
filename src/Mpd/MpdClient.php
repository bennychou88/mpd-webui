<?php
namespace Dinusha\Mpd;


class MpdClient {
    const OK  = 'OK';    
    const ERR = 'ACK';
    const STATE_0 = '0';
    const STATE_1 = '1';
    const PASSWORD = 'password';
    const CLEARERROR = 'clearerror';
    const CURRENTSONG = 'currentsong';
    const IDLE = 'idle';
    const STATUS = 'status';
    const STATS  = 'stats';
    const CONSUME = 'consume';
    const CROSSFADE = 'crossfade';
    const MIXRAMPDB = 'mixrampdb';
    const MIXRAMPDELAY = 'mixrampdelay';
    const RANDOM = 'random';
    const REPEAT = 'repeat';
    const SETVOL = 'setvol';
    const SINGLE = 'single';
    const REPLAY_GAIN_MODE = 'replay_gain_mode';
    const REPLAY_GAIN_STATUS = 'replay_gain_status';
    const VOLUME = 'setvol';

    //Playback options
    const NEXT = 'next';
    const PAUSE = 'pause';
    const PLAY  = 'play';
    const PLAYID = 'playid';
    const PREVIOUS = 'previous';
    const SEEK = 'seek';
    const SEEKID = 'seekid';
    const SEEKCUR = 'seekcur';
    const STOP = 'stop';

    protected $mpdProtoVersion;
    protected $volume;
    protected $repeat;
    protected $random;
    protected $single;
    protected $consume;
    protected $playList; //version number
    protected $playListLength;
    protected $state;
    protected $song;
    protected $songId;
    protected $nextSong;
    protected $nextSongId;
    protected $time;
    protected $elapsed;
    protected $duration;
    protected $bitrate;
    protected $xfade;
    protected $mixRampDB;
    protected $mixRampDelay;
    protected $audio;
    protected $updatingDB;
    protected $error;
    
    protected $stats;
    protected $status;
    
    private $connection;
    private $host;
    private $port;
    private $passwd;

    public function __construct($host, $port, $passwd = null){
        $this->stats = [
            'artists' => 0, 'albums' => 0, 'songs' => 0, 
            'uptime' => 0, 'db_playtime' => 0, 'db_update' => 0, 
            'playtime' => 0];
        
        $this->host = $host;
        $this->port = $port;
        $this->passwd = $passwd;
    }
    
    public function __destruct() {
        if($this->connection){
            fclose($this->connection);
        }
    }

    /**
     * Connect to MPD server
     * 
     * @return type
     */
    public function connect($timeout = 30){
        $this->connection = \stream_socket_client("tcp://$this->host:$this->port", $errno, $errstr, $timeout);
        if (!$this->connection) {
            echo "$errstr ($errno)<br />\n";
            return;
        }

        $resp = fgets($this->connection, 1024);

        if(strncmp(MPDClient::OK, $resp, strlen(MPDClient::OK)) === 0){
            if(strlen($resp) > strlen(MPDClient::OK)){
                $this->mpdProtoVersion = substr($resp, strlen(MPDClient::OK));
            }
        }else{
            echo "Error connecting to MPD, No response\n";
            fclose($this->connection);
            return;
        }

        if(isset($this->passwd)){
            $this->send(MPDClient::PASSWORD.' '.$this->passwd);
        }
        
        //Read stats and status
        $this->updateState();
    }
    
    public function sendCommand($cmd, $params = [], $assoc = false){
        $out = [];
        $cmdLine = $cmd." ". implode(' ', $params);
        
        $resp = $this->send($cmdLine);
        if(empty($this->error)){
            foreach($resp as $line){
                list($key, $value) = explode(': ', $line);
                if($assoc){
                   $out[$key] = $value; 
                }else{
                    array_push($out, array($key => $value));
                }
            }
            
        }else{
            echo 'Error occured '.$this->error."\n";
            return;
        }
        
        return $out;
    }
    
    public function updateState(){
        $resp = $this->sendCommand(MPDClient::STATS, [], true);
        foreach($resp as $key => $val){
            $this->stats[$key] = $val;
        }
        
        $resp = $this->sendCommand(MPDClient::STATUS, [], true);
        foreach($resp as $key => $val){
            $this->$key = $val;
            $this->status[$key] = $val;
        }
    }
    
    public function getState(){
        return [
            'stats' => $this->stats,
            'status' => $this->status
        ];
    }

    // Send a command to mpd
    protected function send($cmd){
        $resp = [];
        //Clear any previous errors
        $this->error = '';
        $cmd = $cmd."\n";

        fwrite($this->connection, $cmd);

        while (!feof($this->connection)) {
            $line = fgets($this->connection, 1024);

            if(strncmp(MPDClient::OK, $line, strlen(MPDClient::OK)) === 0){
                break;
            }

            if(strncmp(MPDClient::ERR, $line, strlen(MPDClient::ERR)) === 0){
                $this->error = substr($line, strlen(MPDClient::ERR));
                break;
            }

            array_push($resp, trim($line));    
        }

        return $resp;
    }
    
    public function getError(){
        return $this->error;
    }
    
    /**
     * Set playback options
     * 
     * @param array $settings
     */
    
    public function setPlaybackOptions(array $settings){
        foreach($settings as $key => $value){
            $this->send($key.' '.$value);
            
            if(!empty($this->error)){
                echo $this->error;
                break;
            }
        }
    }
    
    /**
     * Set the current playback play, pause etc.
     * 
     * @param type $cmd
     * @param type $params
     */
    public function setPlayback($cmd, $params = []){
        $cmdLine = $cmd." ". implode(' ', $params);
        
        $this->send($cmdLine);
        
        if(!empty($this->error)){
                echo $this->error;
        }
    }
}
