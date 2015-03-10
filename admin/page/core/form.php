<?php
/**
 * Page class
 */
class page_core_form extends Page
{
    public $title='Basic Form Usage';

    function init()
    {
        parent::init();

        $this->add('View_Info')->setHTML('This example is created to clarify <a href="http://programmers.stackexchange.com/a/74521/24750">this StackOverflow post</a>, which illustrates how a good framework can make your code very intuitive and easy to read.');


        $form = $this->add('Form');
        $form->setModel('Employee', ['name','salary']);
        $form->addSubmit();

        $form->onSubmit(function($form){
            if ($form['salary'] < 100) {
                return $form->displayError('salary', 'Too little');
            }
            $form->update();
            return 'Employee added';
        });
    }
}

class Model_Employee extends Model {
    function init(){
        parent::init();
        $this->addField('name')->caption('Full Name');
        $this->addField('salary');
    }
}
