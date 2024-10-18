<?php

namespace App\Http\Controllers;

use App\Models\MongoLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends AdminController
{


    protected $withInIndex = ['customer:id,name','user:id,name', 'branch:id,name', 'service:id,name'];
    protected $with = ['customer:id,name','user:id,name', 'branch:id,name', 'service'];

    public function search(&$query, $request)
    {
        if ($request->search != '') {
            $query->whereId('id', $request->search)
                ->orWhere('bankTrxID', $request->search)
                ->orWhere('invoice', $request->search)
                ->orWhereHas(
                    'customer',
                    fn($q) => $q->whereName($request->search)->orWhere('mobile', $request->search)
                );
        }

        if ($request->user_id != '') {
            $query->whereUserId($request->user_id);
        }
        if ($request->branch_id != '') {
            $query->whereBranchId($request->branch_id);
        }
    }
}
