<?php
/**
 * Page class
 */
class page_misc_alertbutton extends Page
{
    public $title='Alert Button';

    /**
     * Initialize the page
     *
     * @return void
     */
    function init()
    {
        parent::init();

        $this->add('View_Info')->set('This page adds a nice alert-button to the top-right of your page with some nice effect.');

        $this->app->layout->add('Button',null,'User_Menu')
            ->set(['Sample Alert', 'swatch'=>'red', 'icon'=>'bell'])
            //->link('/db')
            ->setAttr('title', "ERROR CODE: 123")
            ->js(true)->tooltip();



    }
}
