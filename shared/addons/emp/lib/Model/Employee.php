<?php
namespace emp;


/**
 * This model defines our access to a model table. A typicall table would have
 * an auto-increment ID field, however the sample data uses a custom IDs, so
 * we will have to make those editable and viewable. If you will be adding
 * new employee, you would need to supply emp_no also.
 */
class Model_Employee extends \SQL_Model {
    public $table='employees';
    public $id_field='emp_no';

    function init(){
        parent::init();

        $this->getElement('emp_no')->editable(true)->visible(true);

        $this->addField('birth_date')->type('date')->sortable(true);
        $this->addField('first_name')->sortable(true);
        $this->addField('last_name')->sortable(true);
        $this->addField('gender')->enum(['M','F'])->sortable(true);
        $this->addField('hire_date')->type('date')->sortable(true);
    }

}
