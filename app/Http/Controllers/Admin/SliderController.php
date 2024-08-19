<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Models\Notification;
use App\Models\Slider;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use Globals;

    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index']]) ;
        $this->middleware('permission:' . getPermissionName('create'), ['only' => ['create', 'store']]) ;
        $this->middleware('permission:' . getPermissionName('update'), ['only' => ['edit', 'update']]) ;
        $this->middleware('permission:' . getPermissionName('delete'), ['only' => ['destroy']]) ;
    }

    public function index()
    {
        $models = Slider::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.sliders.index', [
            'models' => $models,
            'pageTitle' => __('Sliders'),
            'createRoute' => route('sliders.create'),
            'editRouteName' => 'sliders.edit',
            'deleteRouteName' => 'sliders.destroy'
        ]);
    }

    public function create()
    {
        return view('admin.sliders.crud', $this->getViewUrl());
    }

    public function getViewUrl($model = null): array
    {
        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'sliders' => [
                'title' => __('Sliders'),
                'route' => route('sliders.index'),
            ],
            'create-sliders' => [
                'title' => __('Create :page', ['page' => __('Slider')]),
                'route' => '#'
            ]
        ];
        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Sliders'),
            'route' => $model ? route('sliders.update', ['slider' => $model->id]) : route('sliders.store'),
            'model' => $model,
            'indexRoute' => route('sliders.index')
        ];
    }

    public function store(StoreSliderRequest $request)
    {
        $model = new Slider();
		$image = $model->storeFile('sliders',$request->file('image')) ;
		if($image){
			$model->image = $image;
		}
		$model->save();
        Notification::storeNewAdminNotification(
            __('New Creation', [], 'en'),
            __('New Creation', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'en'). ' ' . __('Slider', [], 'en') ,
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'ar'). ' ' . __('Slider', [], 'ar') ,
        );

        return $this->getWebRedirectRoute($request, route('sliders.index'), route('sliders.create'));
    }

    public function edit(Slider $slider)
    {
        return view(
            'admin.sliders.crud',
            $this->getViewUrl($slider),
        );
    }

    public function update(StoreSliderRequest $request, Slider $slider)
    {
		$image = $slider->updateFile('sliders',$request->file('image'),$slider->image);
		if($image){
			$slider->image = $image ;
		}
		$slider->save();
        Notification::storeNewAdminNotification(
            __('New Update', [], 'en'),
            __('New Update', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'en') . __('Slider', [], 'en') ,
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'ar') . __('Slider', [], 'ar') ,
        );

        return $this->getWebRedirectRoute($request, route('sliders.index'), route('sliders.edit', ['slider' => $slider->id]));
    }

    public function destroy(Request $request, Slider $slider)
    {
		$slider->removeOldImage($slider->image);
        $slider->delete();
        Notification::storeNewAdminNotification(
            __('New Deletion', [], 'en'),
            __('New Deletion', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'en'). ' ' . __('Slider', [], 'en') ,
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'ar'). ' ' . __('Slider', [], 'ar') ,
        );
		return $this->getWebDeleteRedirectRoute();
    }
}
