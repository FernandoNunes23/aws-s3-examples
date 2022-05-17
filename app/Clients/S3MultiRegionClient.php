<?php


namespace App\Clients;

use Aws\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\Transfer;

/**
 * Class S3MultiRegionClient
 * @package App\Clients
 */
class S3MultiRegionClient
{
    /**
     * @var \Aws\S3\S3MultiRegionClient
     */
    private $client;

    /**
     * S3MultiRegionClient constructor.
     */
    public function __construct()
    {
        // Create a multi-region S3 client
        $this->client = new \Aws\S3\S3MultiRegionClient([
            'version' => 'latest',
        ]);
    }

    /**
     * Remove all the objects and delete the bucket
     *
     * @param string $bucketName
     */
    public function deleteBucket(string $bucketName)
    {
        $objects = $this->client->getIterator('ListObjects', ([
            'Bucket' => $bucketName
        ]));

        foreach ($objects as $object) {
            $this->client->deleteObject([
                'Bucket' => $bucketName,
                'Key' => $object['Key'],
            ]);
        }

        $this->client->deleteBucket([
            'Bucket' => $bucketName,
        ]);
    }

    /**
     * @param string $bucketName
     * @param string $filename
     */
    public function deleteObject(string $bucketName, string $filename)
    {
        $this->client->deleteObject([
            'Bucket' => $bucketName,
            'Key' => $filename,
        ]);
    }

    /**
     * Delete all versioned objects from the bucket and then delete the bucket
     *
     * @param string $bucketName
     */
    public function deleteVersionedBucket(string $bucketName)
    {
        $versions = $this->client->listObjectVersions([
            'Bucket' => $bucketName
        ])->getPath('Versions');

        foreach ($versions as $version) {
            $this->client->deleteObject([
                'Bucket' => $bucketName,
                'Key' => $version['Key'],
                'VersionId' => $version['VersionId']
            ]);
        }

        $this->client->deleteBucket([
            'Bucket' => $bucketName,
        ]);
    }

    public function putObjectWithBody(string $bucketName, string $filename, $body)
    {
        $this->client->putObject([
            'Bucket' => $bucketName,
            'Key' => $filename,
            'Body' => $body
        ]);
    }

    public function getObject(string $bucketName, string $filename)
    {
        return $this->client->getObject([
            'Bucket' => $bucketName,
            'Key' => $filename
        ]);
    }

    /**
     * @param string $bucketName
     * @param string|null $region
     */
    public function createBucket(string $bucketName, ?string $region = null)
    {
        $data = [
            'Bucket' => $bucketName,
        ];

        if (!empty($region) && $region != 'none') {
            $data['@region'] = $region;
        }

        $this->client->createBucket($data);
    }

    /**
     * Transfer a folder to S3 or from S3
     *
     * @param $client
     * @param $source
     * @param $destination
     */
    public function transfer($source, $destination)
    {
        // Create a transfer object
        $manager = new Transfer($this->client, $source, $destination);

        // Perform the transfer synchronously
        $manager->transfer();
    }

    /**
     * @param $source
     * @param string $bucket
     * @param string $key
     */
    public function multiPartUpload($source, string $bucket, string $key)
    {
        $uploader = new MultipartUploader($this->client, $source, [
            'bucket' => $bucket,
            'key' => $key,
            'before_initiate' => function (\Aws\Command $command) {
                // $command is a CreateMultipartUpload operation
            },
            'before_upload' => function (\Aws\Command $command) {
                // $command is an UploadPart operation
            },
            'before_complete' => function (\Aws\Command $command) {
                // $command is a CompleteMultipartUpload operation
            },
            'part_size' => 5242880, //Default 5MB
            'concurrency' => 5, //Default 5
        ]);

        //Recover from errors
        do {
            try {
                $result = $uploader->upload();
            } catch (MultipartUploadException $e) {
                $uploader = new MultipartUploader($this->client, $source, [
                    'state' => $e->getState(),
                ]);
            }
        } while (!isset($result));
    }

    /**
     * @param $source
     * @param string $bucket
     * @param string $key
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function multiPartUploadAsync($source, string $bucket, string $key): \GuzzleHttp\Promise\PromiseInterface
    {
        $uploader = new MultipartUploader($this->client, $source, [
            'bucket' => $bucket,
            'key' => $key,
        ]);

        return $uploader->promise();
    }

    /**
     * @param string $bucketName
     * @return mixed|null
     */
    public function getBucketRegion(string $bucketName)
    {
        return $this->client->determineBucketRegion($bucketName);
    }

    /**
     * @param string $bucketName
     * @return \Aws\Result
     */
    public function getContentsFromABucket(string $bucketName): \Aws\Result
    {
        // Get the contents of a bucket
        return $this->client->listObjects(['Bucket' => $bucketName]);
    }

    /**
     * @param string $bucketName
     * @param string $region
     * @return \Aws\Result
     */
    public function getContentsFromABucketWithRegion(string $bucketName, string $region): \Aws\Result
    {
        // If you would like to specify the Region to which to send a command, do so
        // by providing an @region parameter
        return $this->client->listObjects([
            'Bucket' => $bucketName,
            '@region' => $region,
        ]);
    }

    /**
     * @return \Aws\Result
     */
    public function listBuckets()
    {
        return $this->client->listBuckets();
    }
}
