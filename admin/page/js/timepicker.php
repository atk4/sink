<?php

class page_js_timepicker extends Page
{
    public $title='Time Picker Demo';

    function init()
    {
        parent::init();

        $form = $this->add('Form');
        $form ->addField('TimePicker', 'time');

    }
}

class Form_Field_TimePicker extends Form_Field_Line {
    function getInput() {
        $this->app->jui->addStaticInclude('http://jonthornton.github.io/jquery-timepicker/jquery.timepicker.js');
        $this->app->jui->addStaticStylesheet('http://jonthornton.github.io/jquery-timepicker/jquery.timepicker.css');
        $this->js(true)->timepicker();
        return parent::getInput();
    }
}
