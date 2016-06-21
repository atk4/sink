<?php

class page_js_emailfill extends Page
{
    public $title='Field Fill Demo';

    function init()
    {
        parent::init();

        $form = $this->add('Form');
        $cl = $form ->addField('line', 'client');
        $em = $form ->addField('line', 'client_email', 'Client\'s email');

        // can be used to lookup client's email through a call-back
        $lookup = $this->add('VirtualPage')->set(function() use($em) {


            /*
            $m = $this->add('Model_Client');
            $m->limit(1);
            $m->loadBy('name', 'like', '%'.$_GET['client'].'%');
            $em->js()->val($m['email'])->execute();
             */


            $em->js()->val('hello world, '.$_GET['client'])->execute();
        });

        $cl->on('change')->univ()->ajaxec([$lookup->getURL(), 'client'=>$cl->js()->val()]);

    }
}
