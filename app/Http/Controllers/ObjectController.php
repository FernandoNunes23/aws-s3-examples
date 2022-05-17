<?php


namespace App\Http\Controllers;

use App\Clients\S3MultiRegionClient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ObjectController extends BaseController
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
     * @param $bucketName
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $bucketName)
    {
        $handler = fopen($_FILES['file']['tmp_name'], 'r');

        $this->s3Client->putObjectWithBody($bucketName, $_FILES['file']['full_path'], $handler);

        return back();
    }

    /**
     * @param Request $request
     * @param $bucketName
     * @param $filename
     * @return mixed|null
     */
    public function download(Request $request, $bucketName, $filename)
    {
        $result = $this->s3Client->getObject($bucketName, $filename);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $result['Body']->getSize());
        header('Location: http://localhost');

        return $result['Body'];
    }

    /**
     * @param Request $request
     * @param $bucketName
     * @param $filename
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $bucketName, $filename)
    {
        $this->s3Client->deleteObject($bucketName, $filename);

        return back();
    }
}
