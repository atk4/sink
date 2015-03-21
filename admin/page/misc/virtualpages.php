<?php
class page_misc_virtualpages extends Page {
    public $title='Nested VirtualPage Demo';

    function init() {
        parent::init();

        $this->add('View_Info')->set('This is main page');

        $vp1 = $this->add('VirtualPage','vp1');
        $vp1->set(function ($p1) {

            $vp2 = $p1->add('VirtualPage','vp2');
            $p1->add('View_Info')->set('This is virtual page #1');
            $p1->add('Button')->set('Open Virtual Page 2')->js('click')->univ()->frameURL('Page 2',$vp2->getURL(),['height'=>200, 'width'=>150]);

            $vp2->set(function ($p2){
                $p2->add('View_Info')->set('This is virtual page #2');
            });
        });

        $this->add('Button')->set('Open Virtual Page1')->js('click')->univ()->frameURL('Page 1',$vp1->getURL(),['width'=>500,'height'=>300]);
    }
}
