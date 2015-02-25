<?php
class page_index extends Page
{
    function init()
    {
        parent::init();

        $this->add('H1')->set('Your Frontend');
        $this->add('P')->set('This is a frontend of your web app but it does not have anything yet.');

        $this->add('P')->set('Open frontend/page/index.php file in your text editor and follow documentation.');

        $this->add('Button')
            ->set(array('Agile Toolkit Book', 'icon'=>'book', 'swatch'=>'green'))
            ->link('http://book.agiletoolkit.org/app/frontend.html');
    }
}

class Form_Test extends Form {
    function init() {
        parent::init();
//        $this->add('\\rvadym\\x_tinymce\\Controller_TinyMCE');


        $this->addField('Text','text');
    }
}
