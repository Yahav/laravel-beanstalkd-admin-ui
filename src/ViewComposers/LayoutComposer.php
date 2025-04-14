<?php

namespace Dionera\BeanstalkdUI\ViewComposers;

use Illuminate\View\View;
use Pheanstalk\Contract\PheanstalkManagerInterface;

class LayoutComposer
{
    private PheanstalkManagerInterface $pheanstalk;

    public function __construct(PheanstalkManagerInterface $pheanstalk)
    {
        $this->pheanstalk = $pheanstalk;
    }

    public function compose(View $view): void
    {
        $view->with('tubes', $this->pheanstalk->listTubes());
    }
}
