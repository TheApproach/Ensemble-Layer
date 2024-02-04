<?php

use GuzzleHttp\Exception\ClientException;

require __DIR__ . '/vendor/autoload.php';

session_start();

class OvhApi extends Ovh\Api
{
    public string $applicationKey = 'D3JwM7p1zQ5SqMqm';
    public string $endpoint = 'ovh-ca';
    public string $serviceName = '89e628be5d744bb2acc8f0613a73f75d';
    public string $redirection = 'http://localhost/ovh-api/OvhApi.php';
    private string $applicationSecret = 'hwOxzHTrA4G6Dkhey3pB7qyDxPCkWDrk';
    private string $consumer_key = 'dIYyreEPjKXa1mUlYhjAXg2toUFbHtsC';

    public function __construct()
    {
        if ($this->consumer_key || isset($_SESSION['consumer_key'])) {
            $this->consumer_key = $this->consumer_key ?: $_SESSION['consumer_key'];
            parent::__construct(
                $this->applicationKey,
                $this->applicationSecret,
                $this->endpoint,
                $this->consumer_key
            );

            try {
                $this->get('/auth/currentCredential');
            } catch (ClientException $e) {
                $this->auth();
            } catch (JsonException $e) {
                exit("something went wrong authenticating");
            }
        } else {
            $this->auth();
        }
    }

    public function auth(): void
    {
        // Information about API and rights asked
        $rights = array((object)[
            'method' => 'POST',
            'path' => '*'
        ],
        [
            'method' => 'GET',
            'path' => '*'
        ],
        [
            'method' => 'PUT',
            'path' => '*'
        ],
        [
            'method' => 'DELETE',
            'path' => '*'
        ]
        );

        // Get credentials
        parent::__construct($this->applicationKey, $this->applicationSecret, $this->endpoint);
        $credentials = $this->requestCredentials($rights, $this->redirection);
        // Save consumer key and redirect to authentication page
        $_SESSION['consumer_key'] = $credentials["consumerKey"];
        $this->consumer_key = $credentials["consumerKey"];

        header('location: ' . $credentials["validationUrl"]);
    }

    public function getInstances(): array
    {
        $instances = [];

        try {
            $instances = $this->get("/cloud/project/$this->serviceName/instance");
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            echo $responseBodyAsString;
        } catch (JsonException $e) {
            echo $e->getMessage();
        }
        return $instances;
    }

    public function getLatestEdgeInstance() : array
    {
        $instances = $this->getInstances();
        $latestEdgeCount = 0;
        $latestEdgeInstance = [];

        foreach ($instances as $instance){
            if (strpos($instance['name'], 'suite.fl.edge.') === 0){
                // Determine the latest instance by name e.g. suite.fl.edge.0002 > suite.fl.edge.0001


                $array = explode('.',$instance['name']);
                $counter = (int) end($array);

                if($counter > $latestEdgeCount) {
                    $latestEdgeCount=$counter;
                    $latestEdgeInstance = $instance;
                }

//                if (empty($latestEdgeInstance) || (int) substr($instance['name'], -4) > (int) substr($latestEdgeInstance['name'], -4) )
//
//                    $latestEdgeInstance = $instance;
//                }
            }
        }
        return $latestEdgeInstance;
    }

    public function getImages() :  array
    {
        $images = [];
        try {
            $images = $this->get("/cloud/project/$this->serviceName/image");
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            echo $responseBodyAsString;
        } catch (JsonException $e) {
            echo $e->getMessage();
        }
        return $images;
    }

    public function getNetworks() : array
    {
        $networks = [];
        try {
            $networks = $this->get("/cloud/project/$this->serviceName/network/private");
            foreach ($networks as $network) {
                echo var_export($network) . '</br></br>';
            }
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            echo $responseBodyAsString;
        } catch (JsonException $e) {
            echo $e->getMessage();
        }
        return $networks;
    }

    public function getSshKeys() : array
    {
        $sshKeys = [];
        try {
            $sshKeys = $this->get("/cloud/project/$this->serviceName/sshkey");
            foreach ($sshKeys as $sshKey) {
                echo var_export($sshKey) . '</br></br>';
            }
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            echo $responseBodyAsString;
        } catch (JsonException $e) {
            echo $e->getMessage();
        }
        return $sshKeys;
    }

    public function getNewEdgeName() : string
    {
        $latestEdgeInstance = $this->getLatestEdgeInstance();                // ['id' => '123, 'name' => ''suite.fl.edge.0003' ...]
        $array = explode('.',$latestEdgeInstance['name']);         // [ 'suite','fl','edge','0003']
        $counter = (int) end($array);                                    // 3
        $newInstanceNumber = $counter + 1;                                  // 4
        $newInstanceNumber = sprintf('%04d', $newInstanceNumber);   // '0004'
        return 'suite.fl.edge.' . $newInstanceNumber;                      // 'suite.fl.edge.0004'
    }

    public function addEdgeServer(string $nodeType='edge')
    {
        $config = [
            'flavorId' => 'a8a85ec5-12b4-4b05-8dc1-bd6bd02457d9', // Flavor Details: s1-2 | VCPUs    1 | RAM    2GB |Size    10GB
            'name' => $this->getNewEdgeName(),
            'region' => 'BHS3',
            // 'imageId' => '368510fc-80f8-4f17-9b57-7fdeec51f8aa', // image name: suite.edge.0000 July 2021
            'imageId' => '2b120244-bc90-4da8-b0f4-837f5104d630', // centos 8
            'monthlyBilling' => false,
            'sshKeyId' => '63336c7a64475674', // name: system
            'networks'=> [
                [
                    'networkId' => 'pn-61629_2'
                ]
            ],
            // Connect new server to Gluster cluster so it can find its own init script
            'userData'=>    file_get_contents('/usr/share/suitespace/scripts/deploy/main.sh') .
                PHP_EOL . PHP_EOL .
                'source /usr/share/suitespace/scripts/init/'.$nodeType.'.sh'
            // add its own IP to the scopes table
        ];

        try {
            return $this->post("/cloud/project/$this->serviceName/instance", $config);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            echo $responseBodyAsString;
        }
    }

    public function deleteInstance(string $instanceId): int
    {
        try {
            $this->delete("/cloud/project/$this->serviceName/instance/$instanceId");
            return 0;
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            echo $response->getBody()->getContents();
            return 1;
        } catch(Exception $e) {
            return 1;
        }
    }
}

// print instances
//$api = new OvhApi();
//foreach ($api->getInstances() as $i) {
//    var_export($i);
//    echo '</br></br>';
//}
