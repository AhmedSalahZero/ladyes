<style>


</style>
<div class="dropdown ml-2 transactions">

    <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Transactions') }} </button>
    <div class="dropdown-menu">
        <a data-toggle="modal" data-target="#view-transactions-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Transactions') }} </a>
        <a data-toggle="modal" data-target="#view-fines-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Fines') }} </a>
        <a data-toggle="modal" data-target="#add-fine-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Add Fine') }} </a>
        <a data-toggle="modal" data-target="#view-bonuses-pop{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Bonuses') }} </a>
        <a data-toggle="modal" data-target="#add-bonus-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Add Bonus') }} </a>
        <a data-toggle="modal" data-target="#view-deposits-pop{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Deposits') }} </a>
        <a data-toggle="modal" data-target="#add-deposit-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Add Deposit') }} </a>
        <a data-toggle="modal" data-target="#view-withdrawals-pop{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Withdrawals') }} </a>
        <a data-toggle="modal" data-target="#add-withdrawal-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Add Withdrawal') }} </a>
		@if($model->isClient())
        <a data-toggle="modal" data-target="#view-payments-pop{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Payments') }} </a>
		@endif 
    </div>
    <div class="modal fade" id="view-transactions-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View Transactions For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table datatable datatable-js">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ __('Receiver Name') }}</th>
                                <th class="th-global-class  text-center">{{__('Type')}}</th>
                                <th class="th-global-class  text-center">{{__('Amount')}}</th>
                                <th>{{ __('Transaction Note') }}</th>
                                <th>{{ __('Operation Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->transactions as $index=>$transaction)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td class="text-center">{{$transaction->getUserName()}}</td>
                                <td class="text-center">{{$transaction->getTypeFormatted($lang)}}</td>
                                <td class="text-center">
                                    {{ $transaction->getAmountFormatted() }}
                                </td>
                                <td class="text-center">{{$transaction->getNote($lang)}}</td>
                                <td class="text-center">{{ formatForView($transaction->created_at) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="view-fines-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View Fines For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table datatable datatable-js">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ __('Receiver Name') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Is Paid') }}</th>
                                <th>{{ __('Fine Note') }}</th>
                                <th>{{ __('Operation Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->fines as $index=>$fine)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td class="text-center">{{$fine->getUserName($lang)}}</td>
                                <td class="text-center">{{$fine->getAmountFormatted()}}</td>
                                <td class="text-center">{{$fine->getIsPaidFormatted()}}</td>
                                <td class="text-center">{{$fine->getNoteFormatted()}}</td>
                                <td class="text-center">{{ formatForView($fine->created_at) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="add-fine-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('store.fine.for') }}" method="post">
                    @csrf
                    <input type="hidden" name="model_id" value="{{ $model->id }}">
                    <input type="hidden" name="model_type" value="{{ getTypeWithoutNamespace($model) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Fine To :name' ,['name'=>$model->getFullName($lang)]) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <x-form.input :id="'amount'.$model->id" :label="__('Fine Amount')" :type="'text'" :name="'amount'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Amount')])"></x-form.input>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="view-bonuses-pop{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View Fines For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table datatable datatable-js">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ __('Receiver Name') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Bonus Note') }}</th>
                                <th>{{ __('Operation Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->bonuses as $index=>$fine)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td class="text-center">{{$fine->getUserName($lang)}}</td>
                                <td class="text-center">{{$fine->getAmountFormatted()}}</td>
                                <td class="text-center">{{$fine->getNoteFormatted()}}</td>
                                <td class="text-center">{{ formatForView($fine->created_at) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="add-bonus-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('store.bonus.for') }}" method="post">
                    @csrf
                    <input type="hidden" name="model_id" value="{{ $model->id }}">
                    <input type="hidden" name="model_type" value="{{ getTypeWithoutNamespace($model) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Bonus To :name' ,['name'=>$model->getFullName($lang)]) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <x-form.input :id="'amount'.$model->id" :label="__('Bonus Amount')" :type="'text'" :name="'amount'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Amount')])"></x-form.input>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" id="view-deposits-pop{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View Deposits For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table datatable datatable-js">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ __('Receiver Name') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Payment Method') }}</th>
                                <th>{{ __('Deposit Note') }}</th>
                                <th>{{ __('Operation Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->deposits as $index=>$deposit)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td class="text-center">{{$deposit->getUserName($lang)}}</td>
                                <td class="text-center">{{$deposit->getAmountFormatted()}}</td>
                                <td class="text-center">{{$deposit->getPaymentMethodFormatted()}}</td>
                                <td class="text-center">{{$deposit->getNoteFormatted()}}</td>
                                <td class="text-center">{{ formatForView($deposit->created_at) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="add-deposit-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('store.deposit.for') }}" method="post">
                    @csrf
                    <input type="hidden" name="model_id" value="{{ $model->id }}">
                    <input type="hidden" name="model_type" value="{{ getTypeWithoutNamespace($model) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Deposit To :name' ,['name'=>$model->getFullName($lang)]) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-form.input :id="'amount'.$model->id" :label="__('Deposit Amount')" :type="'text'" :name="'amount'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Amount')])"></x-form.input>
                            </div>
                            <div class="col-md-6">
                                <x-form.select :is-required="true" :is-select2="true" :options="$paymentMethodsFormattedForSelect" :add-new="false" :label="__('Deposit Method')" :all="false" name="payment_method" id="payment_method" :selected-value="'cash'"></x-form.select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>




    <div class="modal fade" id="view-withdrawals-pop{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View Withdrawals For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table datatable datatable-js">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ __('Receiver Name') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Payment Method') }}</th>
                                <th>{{ __('Withdrawal Note') }}</th>
                                <th>{{ __('Operation Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->withdrawals as $index=>$withdrawal)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td class="text-center">{{$withdrawal->getUserName($lang)}}</td>
                                <td class="text-center">{{$withdrawal->getAmountFormatted()}}</td>
                                <td class="text-center">{{$withdrawal->getPaymentMethodFormatted()}}</td>
                                <td class="text-center">{{$withdrawal->getNoteFormatted()}}</td>
                                <td class="text-center">{{ formatForView($withdrawal->created_at) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="add-withdrawal-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('store.withdrawal.for') }}" method="post">
                    @csrf
                    <input type="hidden" name="model_id" value="{{ $model->id }}">
                    <input type="hidden" name="model_type" value="{{ getTypeWithoutNamespace($model) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Withdrawal To :name' ,['name'=>$model->getFullName($lang)]) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-form.input :id="'amount'.$model->id" :label="__('Withdrawal Amount')" :type="'text'" :name="'amount'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Amount')])"></x-form.input>
                            </div>
                            <div class="col-md-6">
                                <x-form.select :is-required="true" :is-select2="true" :options="$paymentMethodsFormattedForSelect" :add-new="false" :label="__('Deposit Method')" :all="false" name="payment_method" id="payment_method" :selected-value="'cash'"></x-form.select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

	@if($model->isClient())
    <div class="modal fade" id="view-payments-pop{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View Payments For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table datatable datatable-js">
                        <thead>
                            <tr>
                                <th class="th-global-class  text-center">#</th>
                                <th class="th-global-class  text-center">{{__('Payment Type')}}</th>
                                <th class="th-global-class  text-center">{{__('Total Price')}}</th>
                                <th class="th-global-class  text-center">{{__('Status')}}</th>
                                <th class="th-global-class  text-center">{{ __('Operation Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($model->payments as $index=>$payment)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td class="text-center">{{$payment->getTypeFormatted()}}</td>
                                <td class="text-center">{{$payment->getTotalPriceFormatted()}}</td>
                                <td class="text-center">{{$payment->getStatusFormatted()}}</td>
                                <td class="text-center">{{ formatForView($payment->created_at) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>

            </div>
        </div>
    </div>
	@endif
</div>
