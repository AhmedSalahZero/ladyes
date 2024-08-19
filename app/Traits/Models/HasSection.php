<?php 
namespace App\Traits\Models;

use App\Enum\InformationSection;

/**
 * * زي مثلا هل هو سائق ولا عميل
 * * زي مثلا قسم (تسجيل الدخول .. 
 */
trait HasSection 
{
	public function getSectionName()
	{
		return $this->section_name ;
	}
	public function getSectionNameFormatted()
	{
		$sectionName = $this->getSectionName();
		$allSections = InformationSection::all();
		return __($allSections[$sectionName]);
	}
	
}
