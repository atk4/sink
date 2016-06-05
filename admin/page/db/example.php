<?php

class page_db_example extends Page {

    public $title='Mocking up README example';

    function init(){
        parent::init();
        $this->person = $this->add('Model_Person');
    }

    function page_index() {

        $t=$this->add('Tabs');

        $tt = $t->addTab('Info');
        $tt->add('H2')->set('Example for Agile Data');
        $tt->add('P')->set('Agile Data is refactor of "Model" layer in Agile Toolkit. While it is still work in progress I am using Agile Toolkit to mock example from the README page. The query that Agile Data would ultimtely generate should be very similar to the one from "Execute Example" tab.');


        $tt->add('Button')->link('http://github.com/atk4/data')->setAttr('target','_blank')
            ->set('Open Agile Data')->addClass('atk-push');


        $tt = $t->addTab('Setup');
        $tt->add('CRUD')->setModel('User');
        $tt->add('CRUD')->setModel('Client');
        $tt->add('CRUD')->setModel('Item');
        $tt->add('CRUD')->setModel('Order');
        $tt->add('CRUD')->setModel('Payment');

        $t->addTabURL('./query','Execute Example');
    }

    function page_query() {

        $m = $this->add('Model_Client');
        $m->addCondition('is_vip', true);

        $o = $this->add('Model_Order');
        $o->addCondition('user_id', 'in', $m->fieldQuery('id'));

        $q = $o->sum('due');

        $this->add('View_Info')->setHTML($q->getDebugQuery());

        $this->add('View_Info')->setHTML((string)$q);
    }

}

class Model_User extends SQL_Model
{
    public $table = 'user';
    function init() {
        parent::init();

        $this->addField('name');
        $this->addField('is_client')->type('boolean');
    }
}
class Model_Client extends Model_User 
{
    function init() {
        parent::init();

        $this->addField('is_vip')->type('boolean');

        $this->addCondition('is_client', true);

        $this->hasMany('Order');
    }
}
class Model_Item extends SQL_Model 
{
    public $table = 'item';
    function init() {
        parent::init();

        $this->addField('name');
        $this->addField('price')->type('money');


    }
}
class Model_Payment extends SQL_Model
{
    public $table = 'payment';
    function init() {
        parent::init();

        $this->addField('amount')->type('money');
        $this->hasOne('Order');
    }
}
class Model_Order extends SQL_Model
{
    public $table = 'order';
    function init() {
        parent::init();

        $this->hasOne('Client');  // not User
        $this->hasOne('Item');
        $this->addField('qty');
        $this->hasMany('Payment');

        $this->addExpression('item_price')->set(function($model, $query){
            return $model->refSQL('item_id')->fieldQuery('price');
        });

        $this->addExpression('paid')->set(function($model, $query){
            return $model->refSQL('Payment')->sum('amount');
        });
  
        $this->addExpression('due')->set(function($model, $query){
            return $query->expr('[0]*[1]-[2]',[
                $model->getElement('item_price'),
                $model->getElement('qty'),
                $model->getElement('paid'),
            ]);
        });

    }
}

