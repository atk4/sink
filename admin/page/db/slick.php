<?php

class page_db_slick extends Page {

    public $title='Comparing Agile ORM with Slick';

    function init(){
        parent::init();
        $this->person = $this->add('Model_Person');
    }

    function page_index() {

        $t=$this->add('Tabs');

        $tt = $t->addTab('Setup');
        $tt->add('CRUD')->setModel($this->person);
        $tt->add('CRUD')->setModel('Address');

        $t->addTabURL('./select','select *');
        $t->addTabURL('./select2','select expr');
        $t->addTabURL('./select3','select expr2');
        $t->addTabURL('./where','where');
        $t->addTabURL('./order','order');
        $t->addTabURL('./max','max');
        $t->addTabURL('./group','group');
        $t->addTabURL('./exprwhere','exprwhere');
        $t->addTabURL('./having','having');
        $t->addTabURL('./join1','join1');
        $t->addTabURL('./subquery','subquery');
        $t->addTabURL('./subquery2','subquery2');
    }

    function page_select(){
        $this->add('View_Info')->setHTML($this->person->selectQuery()->getDebugQuery());

        $this->add('View_Console')->set(function($c){
            foreach($this->person as $row){
                $c->out(json_encode($row->get()));
            }
        });
    }

    function page_select2(){

        $this->person->addExpression('nameref')->set($this->person->dsql()->expr(
            'concat(name, " (", id, ")")'
        ));
        $this->person->setActualFields(['age','nameref']);

        $this->add('View_Info')->setHTML($this->person->selectQuery()->getDebugQuery());

        $this->add('View_Console')->set(function($c){
            foreach($this->person as $row){
                $c->out(json_encode($row->get()));
            }
        });
    }

    function page_select3(){

        $this->person->addExpression('nameref')->set($this->person->dsql()->expr(
            'concat([name], " (", [id], ")")', [
                'name'=>$this->person->getElement('name'),
                'id'=>$this->person->getElement('id'),
            ]
        ));
        $this->person->setActualFields(['age','nameref']);

        $this->add('View_Info')->setHTML($this->person->selectQuery()->getDebugQuery());

        $this->add('View_Console')->set(function($c){
            foreach($this->person as $row){
                $c->out(json_encode($row->get()));
            }
        });
    }

    function page_where(){

        $this->person->addCondition('age','>',18);
        $this->person->addCondition('name','C. Vogt');

        $this->add('View_Info')->setHTML($this->person->selectQuery()->getDebugQuery());

        $this->add('View_Console')->set(function($c){
            foreach($this->person as $row){
                $c->out(json_encode($row->get()));
            }
        });
    }

    function page_order(){

        $this->person->setOrder('age desc, name');

        $this->add('View_Info')->setHTML($this->person->selectQuery()->getDebugQuery());

        $this->add('View_Console')->set(function($c){
            foreach($this->person as $row){
                $c->out(json_encode($row->get()));
            }
        });
    }

    function page_max(){

        $this->add('View_Info')->setHTML($this->person->sum('age')->getDebugQuery());

        $this->add('View_Console')->set(function($c){
            $c->out((String)$this->person->sum('age'));
        });
    }

    function page_group(){

        $q = $this->person->selectQuery();
        $q->del('fields');
        $q->field('address_id');
        $q->field('avg(age)');
        $q->field($this->person->getElement('address'),'address');
        $q->group('address_id');

        $this->add('View_Info')->setHTML($q->getDebugQuery());

        $this->add('View_Console')->set(function($c)use($q){
            foreach($q as $row){
                $c->out(json_encode($row));
            }
        });
    }

    function page_exprwhere(){

        $this->person->addCondition('address','like','%rostokas%');

        $this->add('View_Info')->setHTML($this->person->selectQuery()->getDebugQuery());

        $this->add('View_Console')->set(function($c){
            foreach($this->person as $row){
                $c->out(json_encode($row->get()));
            }
        });
    }

    function page_having(){

        $q = $this->person->selectQuery();
        $q->del('fields');
        $q->field('address_id');
        $q->field(['avgage'=>'avg(age)']);
        $q->field($this->person->getElement('address'),'address');
        $q->group('address_id');
        $q->having('avgage','>',10);

        $this->add('View_Info')->setHTML($q->getDebugQuery());

        $this->add('View_Console')->set(function($c)use($q){
            foreach($q as $row){
                $c->out(json_encode($row));
            }
        });
    }

    function page_join1(){

        $j = $this->person->join('address');

        // joinLeft for left join
        //
        $j->addField('city');

        $this->add('View_Info')->setHTML($this->person->selectQuery()->getDebugQuery());

        $this->add('View_Console')->set(function($c){
            foreach($this->person as $row){
                $c->out(json_encode($row->get()));
            }
        });
    }

    function page_subquery(){

        $m_addr = $this->add('Model_Address');
        $m_addr->addCondition('city','Letchworth');


        $j = $this->person->addCondition('address_id','in',$m_addr->fieldQuery('id'));

        $this->add('View_Info')->setHTML($this->person->selectQuery()->getDebugQuery());

        $this->add('View_Console')->set(function($c){
            foreach($this->person as $row){
                $c->out(json_encode($row->get()));
            }
        });
    }

    function page_subquery2(){

        $this->person->addExpression('rand_id')->set('(select rand() * max(id) from person)');

        $this->add('View_Info')->setHTML($this->person->selectQuery()->getDebugQuery());

        $this->add('View_Console')->set(function($c){
            foreach($this->person as $row){
                $c->out(json_encode($row->get()));
            }
        });
    }
}
