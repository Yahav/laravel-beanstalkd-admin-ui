<?php declare(strict_types=1);

namespace Dionera\BeanstalkdUI\Models;

use Illuminate\Support\Arr;
use Pheanstalk\Values\Job as PheanstalkJob;
use Illuminate\Contracts\Support\Jsonable;
use Pheanstalk\Contract\ResponseInterface;
use Pheanstalk\Values\JobStats;

class Job implements Jsonable
{
    private PheanstalkJob $job;
    private ?JobStats $stats;

    public function __construct(PheanstalkJob $job, ?JobStats $stats = null)
    {
        $this->job = $job;
        $this->stats = $stats;
    }

    public function getId(): int
    {
        return $this->job->getId();
    }

    public function getData(): string
    {
        return $this->job->getData();
    }

    public function getStat(string $name)
    {
        return Arr::get($this->stats, $name);
    }

    public function toJson($options = 0)
    {
        return [
            'id' => $this->job->getId(),
            'data' => $this->job->getData(),
            'stats' => $this->stats,
        ];
    }
}
