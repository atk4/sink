<?php
class page_employees_browse extends Page {
    public $title='Browse Employee Data';
    function page_index(){

        $this->add('View_Info')->set('A few demos to show you how to access your data on record-by-record basis. If you want to look into the code, see "admin/page/employees/browse.php" file.');


        $t = $this->add('Tabs');

        $t_dep = $t->addTab('Departments');
        $t_dep->add('CRUD')->setModel('emp/Model_Department');

        $t->addTabURL('./employees','Employees');
        $t->addTabURL('./salaries','Current Salaries');
    }

    function page_employees(){
        $cr = $this->add('CRUD');
        $cr->setModel('emp/Model_Employee');
        $cr->grid->addPaginator();
        $cr->grid->addQuickSearch(['emp_no','first_name','last_name']);
    }

    /**
     * Page will allow us to explore employee salaries at a gieven point in time.
     * The interface will offer you to enter a custom date and will also show you
     * query used to retrieve data.
     */
    function page_salaries(){

        // Our page recognizes two get arguments. hiring_date=true will
        // use condition on a salary join against a hiring date of an employee
        // effectiely showing you their first salary at the time of hire.
        // It's important to use stickyGET in here, because we don't
        // want those GET arguments got be lost if user changes grid sorting.
        if($this->app->stickyGET('hiring_date')){

            // Specifying date parameter to a model will disable it's default
            // date conditioning and allow us to build condition against
            // defined model field
            $m = $this->add('emp/Model_Employee_Salary',['date'=>false]);
            $m->addCondition('salary_from_date','<=',$m->getElement('hire_date'));
            $m->addCondition('salary_to_date','>=',$m->getElement('hire_date'));
        }else{

            // If date is specified, it will be used in a query. Otherwise
            // null will be passed and model will default to today's date.
            $m = $this->add('emp/Model_Employee_Salary',['date'=>$this->app->stickyGET('date')]);
        }

        // This box is used for displaying header with various information
        // abone the grid.
        $box = $this->add('View_Info');
        $col = $box->add('View_Columns');
        $info = $col->addColumn(2);
        $info->add('H2')->set('Salaries');
        $info->add('P')->set('Displaying query for 100 employee with their salary at a selected point in time.');

        // Second column of our info header will contain a stacked form, where
        // a user will be able to select a custom date for the report.
        $form = $col->addColumn(2)->add('Form',null,null,['form/stacked']);
        $form->addField('DatePicker','date')->set($_GET['date']);
        $form->addSubmit(['Set Date','swatch'=>'blue']);
        $b_hiring = $form->addSubmit('Hiring Date');

        // When form is submitted we will use AJAX reloading to pass
        // an extra parameter for this page.
        $form->onSubmit(function($form)use($b_hiring){
            if($form->isClicked($b_hiring)){
                return $this->js()->reload(['hiring_date'=>true]);
            }
            return $this->js()->reload(['date'=>$form['date'],'hire_date'=>false]);
        });

        // The last column will display query that was used to produce the data.
        // We do not know the query at this point yet, so we will set the
        // contents of this view later.
        $v_query = $col->addColumn(8);
        $v_query->setStyle('overflow-x','auto');

        // Initialize CRUD element, but don't set it up just yet.
        $cr = $this->add('CRUD');

        // Button to use the current model (with implied conditions) to
        // perform a count() query and display list of matched records.
        $cr->grid->addButton('Get Record Count')->onClick(function($b)use($m){
            return 'There are '.$m->count().' employee records matching';
        });

        // Button to use the current model (with implied conditions) to
        // display average salary
        $cr->grid->addButton('Get Average Salary')->onClick(function($b)use($m){
            return 'Avearge salary is '.$m->avg('salary');
        });


        // Limit to 100 records only and only query for relevant fields.
        $m->setLimit(100);
        $cr->setModel($m,['emp_no','first_name','last_name','hire_date','salary','salary_from_date','salary_to_date']);

        // Still allow to do a quick-search on some fields. We will also allow
        // user to perform search on the salary.
        $cr->grid->addQuickSearch(['emp_no','first_name','last_name','salary']);

        // Now that evertyhing is configured, we want to get a query and
        // display it in the header. This will not contain conditions for
        // quick-search or ordering, since those are added dureng render() stage.
        $v_query->add('View')->setElement('code')->setHTML($m->selectQuery()->getDebugQuery());
    }
}
