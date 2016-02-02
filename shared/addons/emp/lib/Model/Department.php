<?php
namespace emp;

class Model_Department extends \SQL_Model {
    public $table='departments';
    public $id_field='dept_no';

    function init(){
        parent::init();

        $this->getElement('dept_no')->editable(true)->visible(true);
        $this->addField('dept_name');
    }

}
