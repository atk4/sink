<?php
/**
 * Page class
 */
class page_realtime_console extends Page
{
    public $title='Realtime Console';

    /**
     * Initialize the page
     *
     * @return void
     */
    function init()
    {
        parent::init();

        // Manual output triggering
        $c=$this->add('View_Console')
            ->set(function($c){
                $c->out('hello');
                $c->err('world');
                sleep(3);
                $c->out('just slept a little, time to wake up....');
                sleep(2);
                $c->err('KNOCK KNOCK');
                $c->jsEval($c->js()->effect('bounce'));
                sleep(2);
                $c->out('OK I\'m up');
            });

        $this->add('View_Console')

            // Executes when process is finished
            ->set(function($c){
                $c->err('Finished!!');
            })

            ->getProcessIO()
            ->exec('./misc/runner.sh','s/l/r/g');


    }
}
