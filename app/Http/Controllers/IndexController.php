<?php


namespace App\Http\Controllers;

use App\Clients\S3MultiRegionClient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class IndexController extends BaseController
{
    /**
     * @var S3MultiRegionClient
     */
    private $s3Client;

    /**
     * IndexController constructor.
     * @param S3MultiRegionClient $s3Client
     */
    public function __construct(S3MultiRegionClient $s3Client)
    {
        $this->s3Client = $s3Client;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $buckets = $this->s3Client->listBuckets()['Buckets'];

        // Add region to bucket array
        foreach ($buckets as $key => $bucket) {
            $buckets[$key]['Region'] = $this->s3Client->getBucketRegion($bucket['Name']);
        }

        return view('index', ['buckets' => $buckets]);
    }
}
