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

        $this->add('View_Terminal')
            ->getProcessIO()
            ->exec('./misc/runner.sh','s/l/r/g');


        return;
        // Alternatively
        $this->add('View_Terminal')
            ->setStream($stream);
    }
}
