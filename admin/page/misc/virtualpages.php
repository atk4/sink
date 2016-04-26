<?php
class page_misc_virtualpages extends Page {
    public $title='Nested VirtualPage Demo';

    function init() {
        parent::init();


        // Demo 1
        $this->add('View_Info')->set('You can get the manual URL of a virtual page and use it as you wish');

        $vp = $this->add('VirtualPage');
        $vp->set(function($p){
            $p->add('View_Info')->set('you are now on a virtual page');
        });

        $this->add('Button')->link($vp->getURL());

        // Demo 2

        $this->add('HR');

        $this->add('View_Info')->set('VirtualPage does not normally render, but you can add it into various views for
            som added effects. For example if you add $vp into a Button, you can use click() method. The second button will only respond if you move your mouse cursor directly over the icon');

        $this->add('Button')->set('Button 1')->add('VirtualPage')->bindEvent()->set(function($p){ 
            $p->add('View_Info')->set('Clicked button 1');
        });

        $this->add('Button')->set(['Button 2','icon'=>'check'])->add('VirtualPage')->bindEvent('Mouseovered the icon?','mouseenter','.icon-check')->set(function($p){ 
            $p->add('View_Info')->set('Hovered icon of Button 2');
        });

        $this->add('HR');

        // Demo 3

        $this->add('View_Info')->set('VirtualPage is also integrated into other views in Agile Toolkit. For example on() event uses Virtual Page for doing call-backs.');

        // Call-back is funneled into enternal VirtualPage.
        $this->add('Button')->set('click me')->on('click',function($b){ return $b->fadeOut('slow'); });

        $this->add('Button')->set(['hover my icon', 'icon'=>'check'])->on('mouseleave','.icon-check',function($b){ return $b->fadeOut('slow'); });

        // Another example is Real-time Console that also uses Virtual Page.

        $this->add('HR');

        // Demo 4

        $this->add('View_Info')->set('As the previous example(s) use custom events and custom selectors, we can now combine virtual page with grid');

        $gr = $this->add('SampleGrid');
        $gr->addColumn('button','test1');
        if(isset($_GET['test1']))$this->js()->univ()->successMessage('Old-style buttons on a grid require checking of _GET argument and executing. ID='.$_GET['test1'])->execute();


        $gr->addColumn('template','test2')->setTemplate('<button data-id="{$id}" class="atk-button-small test2">Test2</button>');
        $gr->on('click', '.test2', function($js, $data){ return $js->univ()->successMessage('With on() and virtual page, we know id='.$data['id']); });


        $vp = $this->add('VirtualPage');
        $gr->addColumn('template','test3')->setTemplate('<button data-id="{$id}" class="atk-button-small test3">Test3</button>');
        $gr->on('click', '.test3')->univ()->dialogURL('Dialog here', [ $vp->getURL(), 'id'=>$this->js()->_selectorThis()->data('id')]);
        $vp->set(function($p){ 
            $p->add('View_Info')->set('Using virtual page we can now get id='.$_GET['id']);
        });


        $vp = $gr->add('VirtualPage');
        $vp->addColumn('test4');
        $vp->set(function($p){
            $p->add('View_Info')->set('A more integrated way to get id='.$p->id);
        });


        $this->add('HR');

        // Demo 5

        $this->add('View_Info')->set('Virtual pages are used to create page within a page. Here you see a button, that opens a "virtual page" that does not have an URL of its own, but will be triggered by a view and will take over rendering, when the dialog is displayed.');

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

class SampleGrid extends Grid {
    function init(){
        parent::init();

        $m = $this->add('Model');
        $m->addField('name');
        $m->addField('surname');
        $m->setSource('Array',[
            ['name'=>'Vinny', 'surname'=>'Sihra'],
            ['name'=>'Zoe', 'surname'=>'Shatwell'],
            ['name'=>'Darcy', 'surname'=>'Wild'],
        ]);

        $this->setModel($m);
    }
}
