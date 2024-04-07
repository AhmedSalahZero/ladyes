<?php 
namespace App\Helpers ;
class HArr 
{
	public static function removeUnderscoreLangFromKeys(array $items):array 
	{
		$result = [];
		foreach($items as $index => $array){
			if(!is_numeric($index)){
				throw new \Exception('Only Numeric Index Array Allowed ');
			}
			foreach($array as $key => $value){
				$key = explode('_',$key)[0] ;
				$result[$index][$key] = $value ;
			}
		}
		return $result ;
	}
	public static function convertStringArrayToArr($arrayAsString):?array 
	{
		// dd(is_string($arrayAsString));
		if(is_string($arrayAsString)){
			return (array)(json_decode($arrayAsString)) ;
		}
		return $arrayAsString;
	} 
	public static function convertStringToArray($string)
	{
		if(!$string){
			return null ;
		}
		return explode(',',$string);
	}
	function formatOptionsForSelect(Collection $items, $idFun = 'getId', $valueFun = 'getName'): array
{
    $formattedData = [];
    foreach ($items as $item) {
        $formattedData[] = [
            'value' => $item->$idFun(),
            'title' => $item->$valueFun(),
        ];
    }

    return $formattedData;
}
}
