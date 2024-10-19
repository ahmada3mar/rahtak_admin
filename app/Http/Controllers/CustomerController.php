<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends AdminController
{


    protected $withInIndex = ['transactions:customer_id'];
    protected $with = [
        'transactions' ,
         'transactions.user:id,name',
         'transactions.branch:id,name',
         'transactions.service:id,name',
        ];



}
