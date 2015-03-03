<?php
class View_Terminal extends View {
    public $process = null;     // ProcessIO, if set
    public $streams = [];     // PHP stream if set.

    /**
     * Sends text through SSE channel. Text may contain newlines
     * which will be transmitted proprely. Optionally you can
     * specify ID also.
     */
    function sseMessageLine($text, $id=null){
        if(!is_null($id))echo "id: $id\n";

        $text = explode("\n", $text);
        $text = "data: ".join("\ndata: ", $text)."\n\n";
        echo $text;
        flush();
    }

    /**
     * Sends text or structured data through SSE channel encoded
     * in JSON format. You may supply id argument.
     */
    function sseMessageJSON($text, $id=null){
        if(!is_null($id))echo "id: $id\n";

        $text = "data: ".json_encode($text)."\n\n";
        echo $text;
        flush();
    }

    function render(){
        if($_GET['sse']){
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Cache-Control: private');
            header('Content-Encoding: none;');
            header("Pragma: no-cache");


            if (ob_get_level()) ob_end_clean();


            // If the process is running, it will have
            // stdout we can read:
            if($this->process){
                // fetch streams
                if(!$this->process->pipes['out']){
                    throw $this->exception('If you associate console with the process, you should execute it.');
                }
                $this->streams[] = $this->process->pipes['out'];
            }

            for($x=1;$x<20;$x++){

                $read = $this->streams; // copy
                $write = $except = [];

                if (($foo=stream_select($read, $write, $except, 5))!== false){
                    foreach($read as $socket){
                    // there could be only one in theory
                        //$data = stream_socket_recvfrom($socket, 10000);
                        $data = rtrim(fgets($socket), "\n");
                        if($data) $this->sseMessageLine($data);
                    }
                }
            }


/*

                $time = date('r');
                $msg= "The server time \nis: {$time}";
                $this->sseMessageJSON($msg);
                sleep(2);
            }
            */



            exit;
        }

        $url=$this->app->url(null,array('sse'=>true));
        $key=$this->getJSID().'_console';


        // TODO: implement this:
        // http://www.qlambda.com/2012/10/smoothly-scroll-element-inside-div-with.html


        parent::render();
        $this->output(<<<EOF
<script>
var source = new EventSource("$url");
var dst = $('#$key');
source.onmessage = function(event) {
    console.log("RECV: "+event.data, event);
    console.log(dst);
    dst.text(dst.text()+event.data+"\\n");
};
</script>
EOF
        );
    }

    function addStream($stream){
        $this->stream[] = $stream;
        return this;
    }

    function getProcessIO(){
        return $this->process = $this->add('System_ProcessIO');
    }

    function defaultTemplate(){
        return array('view/console');
    }

}
