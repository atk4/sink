<?php
class Model_Person extends SQL_Model {
    public $table = 'person';

    function init(){
        parent::init();


        $this->addField('name');
        $this->addField('age');
        $this->hasOne('Address');

        $this->add('dynamic_model/Controller_AutoCreator');
    }

}
