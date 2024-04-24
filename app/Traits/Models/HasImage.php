<?php 
namespace App\Traits\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImage 
{
	public function storeFile(string $storeToFolderName,?UploadedFile $newFile):?string{
		if(is_null($newFile)){
			return null;
		}
		return Storage::disk('public')->putFile($storeToFolderName,$newFile);
	}
	public function updateFile(string $storeToFolderName,?UploadedFile $newFile,?string $oldFilePath):?string{
		$this->removeOldImage($oldFilePath);
		return $this->storeFile($storeToFolderName,$newFile);
	}
	public function removeOldImage(?string $oldFilePath):void
	{
		if($oldFilePath && file_exists('storage/'.$oldFilePath)){
			unlink('storage/'.$oldFilePath);
		}
	}
	public function getFullImagePath(?string $path):string 
	{
		return $path && file_exists('storage/'.$path) ? asset('storage/'.$path) : getDefaultImage() ; ;
	}
	
}
