<?php

class Admin extends App_Admin {

    function init() {
        parent::init();

        $this->api->pathfinder
            ->addLocation(array(
                'addons' => array('addons', 'vendor'),
            ))
            ->setBasePath($this->pathfinder->base_location->getPath() . '/..')
        ;

        $this->template->set('css','compact.css');

        list($prefix) = explode('_',$this->page);
        if($prefix == 'employees'){
            $this->initEmployees();
        }else{
            $this->initBasic();
        }

        $this->layout->menu->js(true)->find('.active')->removeClass('atk-swatch-ink')->addClass('atk-swatch-blue');

    }
    function initLayout(){
        parent::initLayout();
        if(!$_GET['cut_page'])$this->page_object->add('View_ForkMe');

    }

    function initBasic(){
        $sm = $this->api->menu->addMenu('Core Features');

        $sm ->addMenuItem('core/hello', 'Hello World');
        $sm ->addMenuItem('core/form', 'Basic Form');
        $sm ->addMenuItem('core/validation', 'Validation');

        $sm = $this->api->menu->addMenu('JavaScript');

        $sm ->addMenuItem('js/timepicker', 'TimePicker');
        $sm ->addMenuItem('js/boys-n-girls', 'Boys and Girls');
        $sm ->addMenuItem('js/dog-n-cat', 'Dog and Cat');

        $sm = $this->api->menu->addMenu('Agile ORM Compared');
        $sm->addItem('.. with Slick 3.1.0 (scala)','db/slick');

        $sm = $this->api->menu->addMenu('Real-time components');

        $sm ->addMenuItem('realtime/console', 'Real-time console');

        $sm = $this->api->menu->addMenu('Miscelanious');

        $sm ->addMenuItem('misc/alert-button', 'Alert Button');
        $sm ->addMenuItem('misc/virtual-pages', 'Virtual Pages');

        try {
            $this->dbConnect();
        } catch(BaseException $e){
            $this->layout->add('Button',null,'User_Menu')
                ->set(['Set up Database', 'swatch'=>'red', 'icon'=>'attention'])
                ->link('/db')
                ->setAttr('title', $e->getText())
                ->js(true)->tooltip();
        }

    }

    function initEmployees(){
        $br = $this->menu->addItem('Browse Employee Data','employees/browse');

        try {
            $this->dbConnect('dsn-employees');
        } catch(BaseException $e){
            $this->layout->add('Button',null,'User_Menu')
                ->set(['Set up Employees Database', 'swatch'=>'red', 'icon'=>'attention'])
                ->link('/employees')
                ->setAttr('title', $e->getText())
                ->js(true)->tooltip();
        }


    }

    function initTopMenu() {
        $m=$this->layout->add('Menu_Horizontal',['highlight_subpages'=>true,'hover_swatch'=>'blue'],'Top_Menu');
        $m->addItem('Basic Examples','/');
        $m->addItem('Employee DB','/employees');
        $m->addItem('AgileToolkit','/sandbox/dashboard');
        $m->addItem('Documentation','http://book.agiletoolkit.org/');
        $m->js(true)->find('.active')->removeClass('atk-swatch-ink')->addClass('atk-swatch-blue');
    }
}
