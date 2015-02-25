<?php
/**
 * Page class
 */
class page_core_hello extends Page
{
    public $title='Hello World Page';

    /**
     * Initialize the page
     *
     * @return void
     */
    function init()
    {
        parent::init();

        // Current scope is the page ($this) which is a visual object.
        // We can add all sorts of views on this page.

        $this->add('HelloWorld');

        $this->add('HR');

        // More objects can be added and they will appear underneath
        // the ones which are already added.
        $this->add('H3')->set('Nested Objects');

        // Objects can be added into another objects.
        $this->add('View_Info')->add('HelloWorld');

        // You can also configure objects after adding them
        $warning = $this->add('View_Warning');

        // Will memorize in session, that you have clicked close
        $warning->addClose();

        // You can manipulate any object's template directly like this:
        $warning->template->append('label', '2 Paragraphs of dynamic text below');
        $warning->add('LoremIpsum')
            ->setLength(2,20);


        $this->add('HR');

        // Adding your own objects (explained below)
        $col = $this->add('Columns');
        $col->addColumn(5)->add('Hello_Informer');
        $col->addColumn(7)->add('Hello_Informer');

    }
}

/**
 * Displays basic information about itself
 */
class Hello_Informer extends View_Box {
    function init(){
        parent::init();
        $this->setHTML(<<<EOF
<b>Name:</b> {$this->name} <br/>
<b>Short Name:</b> {$this->short_name} <br/>
<b>Owner:</b> {$this->owner} <br/>
<b>Owner<sup>2</sup>:</b> {$this->owner->owner} <br/>
<b>Owner<sup>3</sup>:</b> {$this->owner->owner->owner} <br/>
<b>Owner<sup>4</sup>:</b> {$this->owner->owner->owner->owner} <br/>
<b>Owner<sup>5</sup>:</b> {$this->owner->owner->owner->owner->owner} <br/>
<b>APP:</b> {$this->app} <br/>
EOF
            );
    }
}
