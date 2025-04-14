<?php declare(strict_types=1);

namespace Dionera\BeanstalkdUI\Controllers\Api;

use Pheanstalk\JobId;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Pheanstalk\Exception\ServerException;
use Pheanstalk\Contract\PheanstalkManagerInterface;

class JobsController extends Controller
{
    private PheanstalkManagerInterface $pheanstalk;

    /**
     * JobsController constructor.
     *
     * @param PheanstalkManagerInterface $pheanstalk
     */
    public function __construct(PheanstalkManagerInterface $pheanstalk)
    {
        $this->pheanstalk = $pheanstalk;
    }

    public function delete(int $job): JsonResponse
    {
        try {
            $instance = $this->pheanstalk->peek(new JobId($job));

            $this->pheanstalk->delete($instance);

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully deleted Job',
            ]);
        } catch (ServerException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function kick(int $job): JsonResponse
    {
        try {
            $instance = $this->pheanstalk->peek(new JobId($job));

            $this->pheanstalk->kickJob($instance);

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully kicked job into ready state.',
            ]);
        } catch (ServerException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
