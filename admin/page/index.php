<?php

/**
 * Created by Romans Malinovskis
 */
class page_index extends Page {

    public $title='Dashboard';

    function init() {
        parent::init();

        $this->add('View_ForkMe');


        $this->add('View_Box')
            ->setHTML('Welcome to the Kitchen Sink project. The purpose of this project '.
                ' is to show-case various techniques and examples of Agile Toolkit 4.3. ');

    }

}
