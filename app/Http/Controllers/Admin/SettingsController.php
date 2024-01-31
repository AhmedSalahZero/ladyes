<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettingsRequest;
use App\Models\Driver;
use App\Settings\SiteSetting;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class SettingsController extends Controller
{
	use Globals;
	
	public function __construct()
	{
		// $this->middleware('permission:'.getPermissionName('view') , ['only'=>['index']]);
		// exceptional case 
		$this->middleware('permission:'.getPermissionName('create') , ['only'=>['create','store']]) ; ;
		// $this->middleware('permission:'.getPermissionName('update') , ['only'=>['edit','update']]) ;
		// $this->middleware('permission:'.getPermissionName('delete') , ['only'=>['destroy']]) ;
		
	}

    public function create()
    {
        return view('admin.settings.crud',$this->getViewUrl());
    }
	public function getViewUrl():array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'settings'=>[
				'title'=>__('Settings') ,
				'route'=>route('settings.create'),
			],
			'create-setting'=>[
				'title'=>__('Create :page',['page'=>__('Settings')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Settings'),
			'route'=> route('settings.store') ,
			'model'=>app(SiteSetting::class) ,
			'indexRoute'=>route('settings.create'),
			'drivingRangeFormatted'=>Driver::getDefaultDrivingRangeFormatted()
		];
	}
	
	public function createAppGuidelines()
    {
        return view('admin.app-guidelines.crud',$this->getAppGuidelineViewUrl());
    }
	
	public function getAppGuidelineViewUrl():array 
	{
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'settings'=>[
				'title'=>__('App Guidelines') ,
				'route'=>route('app-guidelines.create'),
			],
			'create-setting'=>[
				'title'=>__('Create :page',['page'=>__('App Guidelines')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('App Guidelines'),
			'route'=> route('app-guidelines.store') ,
			'model'=>app(SiteSetting::class) ,
			'indexRoute'=>route('app-guidelines.create')
		];
	}

    public function store(StoreSettingsRequest $request)
    {
		foreach($request->except(['save','_token']) as $name => $value){
			if($value instanceof UploadedFile && substr($value->getMimeType(), 0, 5) == 'image'){
				$this->storeFile($name,$value);
			}else{
				App(SiteSetting::class)->{$name} = $value ;  
				App(SiteSetting::class)->save();
			}
		}
        return $this->getWebRedirectRoute($request,route('settings.create'),route('settings.create'));
    }
	
	public function storeAppGuidelines(Request $request)
    {
	
		foreach($request->except(['save','_token','guidelines']) as $name => $value){
			App(SiteSetting::class)->{$name} = $value ;  
		}
		App(SiteSetting::class)->app_guideline_items_en = array_column($request->input('guidelines'),'app_guideline_en');
		App(SiteSetting::class)->app_guideline_items_ar = array_column($request->input('guidelines'),'app_guideline_ar');
		App(SiteSetting::class)->save();
        return $this->getWebRedirectRoute($request,route('app-guidelines.create'),route('app-guidelines.create'));
    }
	
	
	
	protected function storeFile(string $name,UploadedFile $newFile):void{
		$oldFilePath = App(SiteSetting::class)->{$name} ;
		if($oldFilePath && file_exists('storage/'.$oldFilePath)){
			unlink('storage/'.$oldFilePath);
		}
		App(SiteSetting::class)->{$name} = Storage::disk('public')->putFile('settings',$newFile) ;  
		App(SiteSetting::class)->save();
	}
 

  

}
