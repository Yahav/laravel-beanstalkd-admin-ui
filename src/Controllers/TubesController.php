<?php declare(strict_types=1);

namespace Dionera\BeanstalkdUI\Controllers;

use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Pheanstalk\Contract\PheanstalkManagerInterface;
use Dionera\BeanstalkdUI\Repositories\JobRepository;
use Pheanstalk\Values\TubeName;

class TubesController extends Controller
{
    private PheanstalkManagerInterface $pheanstalk;
    private JobRepository $jobs;

    public function __construct(PheanstalkManagerInterface $pheanstalk, JobRepository $jobs)
    {
        $this->pheanstalk = $pheanstalk;
        $this->jobs = $jobs;
    }

    public function index(): View
    {
        $tubeNames = collect($this->pheanstalk->listTubes());

        // Adam Wathan give me your strength!
        $tubes = collect($tubeNames)->map(function ($tube) {
            return collect($this->pheanstalk->statsTube($tube))->slice(1)->all();
        })->zip($tubeNames)->flatMap(function (\Illuminate\Support\Collection $pair) {
            // @var \Pheanstalk\Values\TubeName $tubeName
            $tubeName = (string) $pair->get(1);
            return [$tubeName => $pair->get(0)];
        });

        return view('beanstalkdui::tubes.index', compact('tubes'));
    }

    public function showTube(string $tube): View
    {
        $tube = new TubeName($tube);
        $stats = $this->pheanstalk->statsTube($tube);

        $nextReady = $this->jobs->nextReady($tube, true);
        $nextBuried = $this->jobs->nextBuried($tube);
        $nextDelayed = $this->jobs->nextDelayed($tube, true);
        $prefix = config('beanstalkdui.prefix');

        return view('beanstalkdui::tubes.show', compact(
            'nextReady',
            'nextBuried',
            'nextDelayed',
            'stats',
            'tube',
            'prefix'
        ));
    }
}
