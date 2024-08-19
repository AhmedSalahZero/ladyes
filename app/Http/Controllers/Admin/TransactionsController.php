<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Traits\Controllers\Globals;

class TransactionsController extends Controller
{
    use Globals;

    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index']]) ;
        // $this->middleware('permission:'.getPermissionName('update') , ['only'=>['edit','update']]) ;
        // $this->middleware('permission:'.getPermissionName('delete') , ['only'=>['destroy']]) ;
    }

    public function index()
    {
        $models = Transaction::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.transactions.index', [
            'models' => $models,
            'pageTitle' => __('Transactions')
        ]);
    }

    public function getViewUrl($model = null): array
    {
        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'transactions' => [
                'title' => __('Transactions'),
                'route' => route('transactions.index'),
            ],
            // 'create-car-makes'=>[
            // 	'title'=>__('Create :page',['page'=>__('Car Make')]),
            // 	'route'=>'#'
            // ]
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Transactions'),
            'model' => $model,
            'indexRoute' => route('transactions.index')
        ];
    }
}
