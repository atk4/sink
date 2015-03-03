<?php
class View_Terminal extends View {

    function render(){
        if($_GET['sse']){
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header("Cache-Control: private");
            header("Pragma: no-cache");


            if (ob_get_level()) ob_end_clean();

            //for ($i = 0; $i < ob_get_level(); $i++)
//ob_end_flush();
//ob_implicit_flush(1);




            for($x=1;$x<5;$x++){
                echo "data: xxxx =  $x\n";
                $time = date('r');
                echo "data: The server time is: {$time}\n\n";
                flush();
                sleep(2);
            }



            exit;
        }

        $url=$this->app->url(null,array('sse'=>true));

        $this->output(<<<EOF
<script>
var source = new EventSource("$url");
source.onmessage = function(event) {
    console.log("RECV: "+event.data, event);
};
</script>
EOF
        );

        parent::render();
    }

    function getProcessIO(){

        return $this->add('System_ProcessIO');

    }
    function defaultTemplate(){
        return array('view/console');
    }

}
