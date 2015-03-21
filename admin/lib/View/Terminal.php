<?php
class View_Terminal extends View {
    public $process = null;     // ProcessIO, if set
    public $streams = [];     // PHP stream if set.

    public $prefix = [];

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
                $this->addStream($this->process->pipes['out']);
                $this->addStream($this->process->pipes['err'],'ERR','#f88');
            }

            while($this->streams){
                $read = $this->streams; // copy
                $write = $except = [];

                if (($foo=stream_select($read, $write, $except, 5))!== false){
                    foreach($read as $socket){
                        $data = fgets($socket);
                        if($data === false){
                            if(($key = array_search($socket, $this->streams)) !== false) {
                                unset($this->streams[$key]);
                            }
                            continue;
                        }
                        $data = ['text'=>rtrim($data, "\n")];

                        $s=(string)$socket;
                        if($this->prefix[$s]){
                            $data['text']=$this->prefix[$s].": ".$data['text'];
                        }
                        if($this->color[$s]){
                            $data['style']='color: '.$this->color[$s];
                        }

                        if($data) $this->sseMessageJSON($data);
                    }
                }
            }

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
    var data=$.parseJSON(event.data);
    var text=data.text;

    if(data.class)text='<span class="'+data.class+'">'+text+'</span>';
    if(data.style)text='<span style="'+data.style+'">'+text+'</span>';

    dst.html(dst.html()+text+"\\n");
    var height = dst[0].scrollHeight;
    console.log(height);
    dst.stop().animate({scrollTop:height});

};
source.onerror = function(event) {
    event.target.close();
}
</script>
EOF
        );
    }

    function addStream($stream, $prefix=null, $color=null){
        $this->streams[] = $stream;

        if(!is_null($prefix)){
            $this->prefix[(string)$stream] = $prefix;
        }
        if(!is_null($color)){
            $this->color[(string)$stream] = $color;
        }
        return $this;
    }

    function getProcessIO(){
        return $this->process = $this->add('System_ProcessIO');
    }

    function defaultTemplate(){
        return array('view/console');
    }

}
