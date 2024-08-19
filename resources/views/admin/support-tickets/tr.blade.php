<tr data-id="{{ $model->id }}" class="deleteable-row">
    <td class="text-center">{{$loop->iteration}}</td>
    <td class="text-center">{{$model->getUserName($lang)}}</td>
    <td class="text-center">{{$model->getModelTypeFormatted()}}</td>
    <td class="text-center">{{$model->getSubject($lang)}}</td>
    <td class="text-center">{{$model->getMessage($lang)}}</td>

    @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
    <td class="d-flex align-items-center justify-content-sm-center">
        @if($user->can(getPermissionName('update')))
        <a href="{{route($editRouteName,$model->id)}}" class="block-page ml-2 btn btn-primary btn-sm"><i class="la la-pencil"></i></a>
        <a title="{{ __('Edit Prices') }}" href="#" class="block-page ml-2 btn btn-primary btn-sm " data-toggle="modal" data-target="#edit-prices-per-country-{{ $model->id }}"><i class="la la-money"></i></a>
        <div class="modal fade" id="edit-prices-per-country-{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                <div class="modal-content">
                    <form action="{{ route('car-sizes.update.prices',['carSize'=>$model->id]) }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Car Size Price :name',['name'=>$model->getName($lang)]) }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>
                                        {{ __('Country Name') }}
                                    </th>
                                    <th>
                                        {{ __('Price') }}
                                    </th>
                                </tr>
                                @foreach($model->countryPrices as $countryWithPriceAsPivot)
                                <tr>

                                    <td>
                                        <input class="form-control" disabled value="{{ $countryWithPriceAsPivot->getName($lang) }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center" style="gap:0.4rem">
                                            <input required name="prices[{{ $countryWithPriceAsPivot->id }}]" type="numeric" step="0" value="{{ $countryWithPriceAsPivot->pivot->price }}" class="form-control"> <span>
                                                {{ $countryWithPriceAsPivot->getCurrencyFormatted() }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary js-save-by-ajax">{{ __('Save') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        @endif
    </td>
    @endif
</tr>
