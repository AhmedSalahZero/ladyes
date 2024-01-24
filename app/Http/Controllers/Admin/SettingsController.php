<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettingsRequest;
use App\Models\Driver;
use App\Settings\SiteSetting;
use App\Traits\Controllers\Globals;
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
	
	protected function storeFile(string $name,UploadedFile $newFile):void{
		$oldFilePath = App(SiteSetting::class)->{$name} ;
		if($oldFilePath && file_exists('storage/'.$oldFilePath)){
			unlink('storage/'.$oldFilePath);
		}
		App(SiteSetting::class)->{$name} = Storage::disk('public')->putFile('settings',$newFile) ;  
		App(SiteSetting::class)->save();
	}
 

  

}
