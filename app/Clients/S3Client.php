<?php


namespace App\Clients;

/**
 * Class S3Client
 * @package App\Clients
 */
class S3Client
{
    /**
     * @var \Aws\S3\S3Client
     */
    private $client;

    /**
     * S3Client constructor.
     */
    public function __construct()
    {
        $this->client = new \Aws\S3\S3Client([
            'version' => '2006-03-01',
            'region'  => 'us-east-1'
        ]);
    }
}
