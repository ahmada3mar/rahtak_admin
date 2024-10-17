<?php

namespace App\Http\Controllers;

use App\Helpers\Sadad;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SadadController extends Controller
{
    public function finance()
    {
        $sadad = (new Sadad());
        return  $sadad->finance()->json();
    }

    public function billerType()
    {
        $sadad = (new Sadad());
        $response = $sadad->billerType();
        return \array_merge(["data"=>$response->json() ], $sadad->finance()->json());
    }

    public function billerInfo($type)
    {
        $sadad = (new Sadad());
        $response = $sadad->billerInfo($type);
        return \array_merge(["data"=>$response->json() ], $sadad->finance()->json());
    }

    public function serviceInfo($service)
    {
        $sadad = (new Sadad());
        $response = $sadad->serviceInfo($service);
        return \array_merge(["data"=>$response->json() ], $sadad->finance()->json());

    }

    public function inquire(Request $request)
    {

        $data = [
            'deviceNo' => "xxx",
            'paymentType' => $request->paymentType,
            'prepaidCategory' => $request->catValue,
            'containsPrepaidCats' => $request->containsPrepaidCats ? 'true' : 'false',
            'billingNoRequired' =>$request->billingNoRequired ? 'true' : 'false',
            'billingNo' => $request->billingNo,
            'billerName' => $request->service,
            'serviceId' => $request->id, // Assuming you're using Laravel's routing helper
            'idType' => 'undefined',
            'identification' => null,
            'nationality' => null,
            'bank_PSP_Type' => false,
            'customerName' => null,
            'mobile' => null,
            'paymentAmount' => $request->paymentAmount,
            'billerCode' => $request->code,
            'language' => 2,
            'remoteIP' => "",
        ];


        $sadad = (new Sadad());
        $response = $sadad->inquire($data);

        if (!$response->json('amount')) {
            \info(\json_encode( $request->json()));
            throw new HttpException(400, 'لا يوجد معلومات');
        }

        return \array_merge(["data"=>$response->json() ], $sadad->finance()->json());

    }


    public function pay(Request $request, Service $service)
    {

        $customer = Customer::updateOrCreate(
            ['mobile' => $request->clientMobile],
            ['name' => $request->clientName]
        );

        $data = [
            'posId' => 57128,
            'totalPayment' => $request->totalPayment,
            'dueAmount' => $request->dueAmount,
            'categoryNo' => '',
            'categoryName' => '',
            'billingNo' => $request->billingNo,
            'billerType' => $request->billerType,
            'billerName' => $request->billerName,
            'billerCode' => $request->billerCode,
            'serviceTypeNo' => $request->serviceTypeNo,
            'idType' => null,
            'identification' => null,
            'nationality' => null,
            'transType' => $request->transType,
            'bank_PSP_Type' => false,
            'amount' => $request->amount,
            'fees' => $request->fees,
            'additionalAmount' => $request->additionalAmount,
            'boothId' => 0,
            'containsPrepaidCats' => $request->containsPrepaidCats ? 'true' : 'false',
            'billingNoRequired' => $request->billingNoRequired ? 'true' : 'false',
            'validationCode' => $request->validationCode,
            'clientName' => $customer->name,
            'clientMobile' => $customer->mobile,
            'clientNationalNo' => null,
            'aliasName' => 'rahtak',
            'language' => 2,
            'remoteIP' => "",
            'deviceNo' => "xxx",
        ];

        Transaction::create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'user_id'=> Auth::user()->id,
            'branch_id'=> Auth::user()->branch_id,
            'amount' => $request->amount,
            'fees' => $request->fees,
            'additional_amount' => $request->additionalAmount,
            'rahtak_fees' => $request->rahtak_fees,
        ]);


        $sadad = (new Sadad());
        $response = $sadad->pay($data);


        if ($response->json('errorMsg')) {
            throw new HttpException(400, $response->json('errorMsg'));
        }


        return \array_merge(["data"=>$response->json() ], $sadad->finance()->json());
    }

    public function serviceDetails($service)
    {
        return (new Sadad())->serviceDetails($service);
    }
}
