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

        $sm = $this->api->menu->addMenu('Core Features');

        $sm ->addMenuItem('core/hello', 'Hello World');
    }
}



        // For improved compatibility with Older Toolkit. See Documentation.
        // $this->add('Controller_Compat42')
        //     ->useOldTemplateTags()
        //     ->useOldStyle()
        //     ->useSMLite();
