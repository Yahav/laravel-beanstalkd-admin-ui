<?php declare(strict_types=1);

namespace Dionera\BeanstalkdUI\Repositories;

use Dionera\BeanstalkdUI\Models\Job;
use Pheanstalk\Exception\ServerException;
use Pheanstalk\Contract\ResponseInterface;
use Pheanstalk\Contract\PheanstalkManagerInterface;
use Pheanstalk\Values\JobState as PheanstalkJob;
use Pheanstalk\Values\JobStats;
use Pheanstalk\Values\TubeName;

class JobRepository
{
    private PheanstalkManagerInterface $pheanstalk;

    public function __construct(PheanstalkManagerInterface $pheanstalk)
    {
        $this->pheanstalk = $pheanstalk;
    }

    public function nextReady(TubeName $tube, bool $withStats = false): ?Job
    {
        return $this->next(PheanstalkJob::READY, $tube, $withStats);
    }

    public function nextDelayed(TubeName $tube, bool $withStats = false): ?Job
    {
        return $this->next(PheanstalkJob::DELAYED, $tube, $withStats);
    }

    public function nextBuried(TubeName $tube, bool $withStats = false): ?Job
    {
        return $this->next(PheanstalkJob::BURIED, $tube, $withStats);
    }

    public function getStats(\Pheanstalk\Values\Job $instance): ?JobStats
    {
        return $this->pheanstalk->statsJob($instance);
    }

    private function next(\Pheanstalk\Values\JobState $type, TubeName $tube, bool $withStats = false): ?Job
    {
        try {
            $type = $type->value;
            $method = 'peek' . ucfirst($type);

            $this->pheanstalk
                ->useTube($tube);

            $instance = $this->pheanstalk->{$method}();

            if ($instance === null) {
                return null;
            }

            if ($withStats) {
                return new Job($instance, $this->getStats($instance));
            }

            return new Job($instance);
        } catch (ServerException $e) {
            return null;
        }
    }
}
