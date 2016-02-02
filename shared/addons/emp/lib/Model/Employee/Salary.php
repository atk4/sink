<?php
namespace emp;

/**
 * This model can dynamically calculate salaries for our employees for a
 * desired time.
 *
 * $m = $this->add('emp/Model_Employee_Salary', ['date'=>$date]);
 *
 * $date => null         -- will use today's date
 * $date => '1990-01-01' -- will set a custom date
 * $date => false        -- will not set date condition leaving it to you
 */
class Model_Employee_Salary extends Model_Employee {

    protected $date = null;

    function init(){
        parent::init();

        if($this->date === null){
            $this->date = date('Y-m-d');
        }

        // Creates join with a salaries table and defines necessary fields
        $j_salary = $this->join('salaries.emp_no');
        $j_salary->addField('salary_from_date','from_date')->type('date');
        $j_salary->addField('salary_to_date','to_date')->type('date');
        $j_salary->addField('salary')->sortable(true);


        // We leave an option to disable automatic inclusion of this condition
        // in case we want to manually define conditions
        if($this->date)$this->addDateCondition();
    }

    function addDateCondition(){
        $this->addCondition('salary_from_date','<=',$this->date);
        $this->addCondition('salary_to_date','>=',$this->date);
    }


    // This method is missing from ATK, so adding manually (copy-pasted from sum())
    function avg($field)
    {
        // prepare new query
        $q = $this->dsql()->del('fields')->del('order');

        // put field in array if it's not already
        if (!is_array($field)) {
            $field = array($field);
        }

        // add all fields to query
        foreach ($field as $f) {
            if (!is_object($f)) {
                $f = $this->getElement($f);
            }
            $q->field($q->expr('avg([0])',[$f]), $f->short_name);
        }

        // return query
        return $q;
    }

}
