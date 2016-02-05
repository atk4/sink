<?php
class Model_Address extends SQL_Model {
    public $table = 'address';
    public $title_field = 'street';

    function init(){
        parent::init();


        $this->addField('street');
        $this->addField('city');
        $this->hasMany('Person');

        $this->add('dynamic_model/Controller_AutoCreator');
    }

}
