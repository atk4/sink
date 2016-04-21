<?php
/**
 * Thanks to https://forum.agiletoolkit.org/t/why-do-i-have-to-use-a-chain-of-return-after-a-js-reload/188
 * for this example idea
 */
class page_js_dogncat extends Page
{
    public $title='Dog and Cat - javascript element reloading';

    /**
     * Initialize the page
     *
     * @return void
     */
    function init()
    {
        parent::init();

        $vm=$this->add('View_Box')->set('Nothing');

        if($_GET['dog'])$vm->set('It is the dog');
        if($_GET['cat'])$vm->set('It is the cat');

        $button=$this->add('Button');
        $button->set('Dog');
        $button->on('click',$vm->js()->reload(['dog'=>true]));

        $button=$this->add('Button');
        $button->set('Cat');
        $button->on('click',$vm->js()->reload(['cat'=>true]));

    }
}
