<?php
/**
 * Page class
 */
class page_js_boysngirls extends Page
{
    public $title='Boys and Girls - javascript element reloading';

    /**
     * Initialize the page
     *
     * @return void
     */
    function init()
    {
        parent::init();

        $m_types=$this->add('Model');
        $m_types->addField('name');
        $m_types->setSource('Array', ['Alice', 'John', 'Peter', 'Annie']);
        $g1 = $this -> add('Grid');
        $g1->setModel($m_types,['name']);
        $g1->addButton('Filter Girls')->js('click', $g1->js()->reload(['gender'=>'F']));
        $g1->addButton('Filter Boys')->js('click', $g1->js()->reload(['gender'=>'M']));



        $m_types=$this->add('Model');
        $m_types->addField('name');
        $m_types->setSource('Array', ['All', 'Green', 'Blue', 'Red']);

        $g1->addClass('atk-push'); // add spacing after grid

        $this->add('View_Info')->set('Next example demonstrates how you can use reloading event inside dynamicaly generated content, such as grid rows');

        $cc=$this->add('Columns');
        $c1 = $cc->addColumn(6);

        $c1->add('H2')->set('Select Type');
        $g1 = $c1 -> add('Grid');
        $g1 ->setModel($m_types);
        $g1->js(true)->find('tbody tr')->hover(
            $g1->js()->_selectorThis()->addClass('atk-swatch-blue')->_enclose(),
            $g1->js()->_selectorThis()->removeClass('atk-swatch-blue')->_enclose()
            )->css(['cursor'=>'pointer']);


        $c2 = $cc->addColumn(6);
        if($_GET['type_id']){
            $c2->add('H2')->set('Showing type='.$_GET['type_id']);
        }else{
            $c2->add('H2')->set('Showing for all results. Rand='.rand());
        }

        $g1->on('click', 'tbody tr', $c2->js()->reload(['type_id'=>$this->js()->_selectorThis()->data('id')]));

    }
}
