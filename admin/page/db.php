<?php
/**
 * Page class
 */
class page_db extends Page
{
    public $title='Database Setup';

    /**
     * Initialize the page
     *
     * @return void
     */
    function init()
    {
        parent::init();

        $this->add('P')->set('Some parts of this application require database connection.');

        $this->add('P')->set('1. Install MySQL locally with new, empty database');
        $this->add('P')->set('2. Open file admin/config.php and paste the following text:');

        $this->add('View')->setElement('pre')->set("<?php\n\$config['dsn']='mysql://root:pass@localhost/dbname';");

        $this->add('P')->set('3. You are done, refresh the page');

    }
}
