<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Sadad
{
    private $http;

    public function __construct()
    {
        $token = $this->getToken();
        $url = \config('app.sadad_url');
        $this->http = Http::baseUrl($url)->withToken($token);
    }

    public function billerType()
    {
        return  $this->http->get("/Payment/GetBillerType?deviceNo=xxx");
    }

    public function finance()
    {
        return  $this->http->get("/Home/GetFinancialInfo");
    }

    public function billerInfo($type)
    {
        return  $this->http->get("/Payment/GetBillerInfo?deviceNo=xxx&BillerType=" . $type);;
    }

    public function serviceInfo($service)
    {
        return  $this->http->get("/Payment/GetServiceInfoByBiller?deviceNo=xxx&BillerName=" . $service);
    }
    public function serviceDetails($service)
    {
        $response1 =  $this->http->get("/Payment/GetServiceDetailsByServiceID?deviceNo=xxx&ServiceID=" . $service);
        $response =  $this->http->get("/Payment/GetServiceCategoriesByServiceID?deviceNo=xxx&ServiceID=" . $service);

        return [
            'input' => Arr::first($response1->json()),
            'cat' => $response->json(),
        ];

    }

    public function inquire($data)
    {
        return  $this->http->asForm()->post("/Payment/Inquire", $data);
    }

    public function pay($data)
    {
        return  $this->http->asForm()->post("/Payment/pay", $data);
    }

    private function login()
    {
        $data = [
            'UserName' =>\config('app.UserName'),
            'Password' =>\config('app.Password'),
            'RemoteIP' =>\config('app.RemoteIP'),
            'MacAddress' =>\config('app.MacAddress'),
            'LanguageId' =>2
        ];

        $response = Http::asForm()->post(config('app.sadad_url') ."/User/v1/LoginPOS" , $data);

        if($response->json('errorMessage')){
            throw new HttpException(400 ,$response->json('errorMessage') );
        }

        return $response->json('response')['token'];
    }

    public function getToken()
    {
        if(Cache::has('sadad_token')){
            return Cache::get('sadad_token');
        }else{
            $token = $this->login();
            Cache::put('sadad_token' , $token , 600);
            return $token;
        }
    }
}
