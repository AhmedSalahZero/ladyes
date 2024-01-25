@props([
'deleteRoute',
'modelId',
'fullDeleteRoute'=>'',
'modelFullId'=>''
])
<div class="modal inner-modal fade text-left" id="{{ $modelFullId ?: 'delete-modal-'.$modelId }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">{{__('msg.delete_item')}}</h4>
                <button type="button" class="close" data-dismiss-modal="inner-modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{asset('assets/images/remove.png')}}">
                <h5>{{__('Are You Sure To Delete This Item')}}</h5>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss-modal="inner-modal">{{__('Back')}}</button>
            <form method="post" action="{{ $fullDeleteRoute ?: route($deleteRoute,[$modelId]) }}" >
				@method('delete')
				@csrf
                    <button  type="submit" class="btn btn-outline-danger js-save-by-ajax">{{__('Confirm')}}</button>
            </form>
                </div>
        </div>
    </div>
</div>
