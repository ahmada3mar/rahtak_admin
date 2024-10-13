<?php

namespace App\Helpers;

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
        return  $this->http->get("/GetBillerType?deviceNo=xxx");
    }

    public function billerInfo($type)
    {
        return  $this->http->get("/GetBillerInfo?deviceNo=xxx&BillerType=" . $type);;
    }

    public function serviceInfo($service)
    {
        return  $this->http->get("/GetServiceInfoByBiller?deviceNo=xxx&BillerName=" . $service);
    }
    public function inquire($data)
    {
        return  $this->http->asForm()->post("/Inquire", $data);
    }

    public function pay($data)
    {
        return  $this->http->asForm()->post("/pay", $data);
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

        $response = Http::asForm()->post("https://sadad.prosysjo.com/POS/User/v1/LoginPOS" , $data);

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
            Cache::put('sadad_token' , $token);
            return $token;
        }
    }
}
