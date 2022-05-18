<?php


namespace App\Http\Controllers;

use App\Clients\S3MultiRegionClient;
use Aws\S3\Exception\S3Exception;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class BucketController extends BaseController
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

    public function view(Request $request, $name)
    {
        $bucketContent = $this->s3Client->getContentsFromABucket($name)['Contents'];

        return view('bucket.view', ['bucketContent' => $bucketContent, 'bucketName' => $name]);
    }

    public function delete(Request $request, $name)
    {
        try {
            $this->s3Client->deleteBucket($name);
            $message = 'ok';
        } catch (S3Exception $e) {
            $message = $e->getMessage();
        }

        return redirect()->route('index')->with('message', $message);
    }

    public function create()
    {
        return view('bucket.create');
    }

    public function store(Request $request)
    {
        try {
            //$this->s3Client->createBucketWebsite($request->bucketName, $request->region);
            $this->s3Client->createBucket($request->bucketName, $request->region);
            $message = 'ok';
        } catch (S3Exception $e) {
            $message = $e->getMessage();
        }

        return redirect()->route('index')->with('message', $message);
    }
}
