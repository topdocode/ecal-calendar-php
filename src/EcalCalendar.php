<?php 

namespace Topdocode\EcalCalendar;
use GuzzleHttp\Client;

class EcalCalendar
 {
    public string $apiKey;

    public string $apiSecret;

    public string $apiSign = "";
    public $client; 
    function __construct ($apiKey, $apiSecret){
        // $this->apiKey = config('app.ecal.api_key');
        // $this->apiSecret = config('app.ecal.api_secret');
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->client = new Client(
            [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );
    }

    public function createEcalApiSign($filter, $method ='GET')
    {
        // ksort($filter);
        // dd($filter);
        if($method == 'GET'){
            $data = array_map(function ($key, $value) {
                return $key . $value;
            }, array_keys($filter), array_values($filter));
    
            $filter = join('', $data);
        }

        if($method == 'POST' || $method == 'PUT'){
            $filter = json_encode($filter);
        }


        return $this->apiSign= md5($this->apiSecret."apiKey".$this->apiKey.$filter);
    }

    public function callAPI($type, $id =null, $params=[], $method='GET')
    {

            $apiSign = $this->createEcalApiSign($params, $method);

            if($method != 'GET'){
                $params = [];
            }

            $newParams = array();
            foreach ($params as $key => $value) {
                if(array_key_first($params) == $key){
                    $newParams[$key] =  "&" .$key . '=' . $value;
                }else{
                    $newParams[$key] = $key . '=' . $value;
                }
            }

            if($id !=null){
                $type = $type . "/" .$id ."?";
            }else{
                $type = $type."?";
            }
            if(count($newParams) > 0){
                $params=implode("&",$newParams);
            }else{
                $params = '';
            }


        return "https://api.ecal.com/apiv2/".$type."apiKey=".$this->apiKey."&apiSign=".$apiSign.$params;
    }

    public function getCalendar($filter=[])
    {
        // this data for try
        // $filter = [
        //     'showDraft' => 1
        // ];

        // $response = Http::get($this->callAPI('calendar',null,$filter));
        $response = $this->client->get( $this->callAPI('calendar',null,$filter));
        
        return [
            'data' =>json_decode($response->getBody()->getContents())->data,
            'status' => $response->getStatusCode(),
        ];
    }

    public function getCalendarById($filter=[], $id)
    {
        // this data for try
        // $filter = [
        //     'showDraft' => 1
        // ];

        $response = $this->client->get($this->callAPI('calendar',$id,$filter));
        return json_decode($response->getBody()->getContents(), true);
    }

    public function createCalendar($data = [])
    {

        // $apiSign = $this->createEcalApiSign($data, 'POST');
        $response = $this->client->post($this->callAPI('calendar',null,$data,'POST'), 
            [
                'json' => $data
            ]);
        // return $response->getBody()->getContents();    
        $response = json_decode($response->getBody()->getContents(), true);
        return  $response;   
    }

    public function updateCalendar($data, $id)
    {
        // $response = Http::put($this->callApi('calendar', $id), $data);

        // return $response->json();
        // $apiSign = $this->createEcalApiSign($data, 'PUT');
        $response = $this->client->put($this->callAPI('calendar',$id, $data, 'PUT'), 
            [
                'json' => $data
            ]
        );
        $response = json_decode($response->getBody()->getContents(), true);
        return  $response;  
    }

    public function getEvent($filter=[])
    {
        // this data fr try
        // $filter = [
        //     'calendarIds' => '64229d018723a1000e07dc9f',
        //     // 'endDate' => '2023-11-20',
        //     'showPastEvents' => 'true',
        //     // 'startDate' => '2022-11-20'
        // ];
        $response = $this->client->get($this->callAPI('event',null,$filter));
        return $response;
    }

    public function createEvent($data)
    {
        $response = $this->client->post($this->callAPI('event',null,$data,'POST'), 
        [
            'json' => $data
        ]);
    // return $response->getBody()->getContents();    
    $response = json_decode($response->getBody()->getContents(), true);
    return  $response;   

    }

    public function updateEvent($data, $id)
    {
        $response = $this->client->put($this->callAPI('calendar',$id, $data, 'PUT'), 
            [
                'json' => $data
            ]
        );
        $response = json_decode($response->getBody()->getContents(), true);
        return  $response;  
    }

    // public function deleteCalendar()
    // {
        
    // }
}