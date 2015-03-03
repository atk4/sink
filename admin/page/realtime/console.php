<?php
/**
 * Page class
 */
class page_realtime_console extends Page
{
    public $title='Realtime Console';

    /**
     * Initialize the page
     *
     * @return void
     */
    function init()
    {
        parent::init();

        $io = $this->add('View_Terminal')->getProcessIO();
        $io->debug();

        return;

        $io->exec('/usr/bin/sed','s/l/r/g');

        $io->write('coca cola');
        $out=$io->select();

        var_dump($out);

return;
        $this->expects($out,'coca cora');

        $io->write('little love');
        $out=$io->read_line();
        $this->expects($out,'rittre rove');

        $this->terminate();
    }
}
