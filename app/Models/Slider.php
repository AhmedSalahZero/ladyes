<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasCreatedAt;
use App\Traits\Models\HasImage;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model 
{
    use  IsBaseModel,HasDefaultOrderScope  , HasFactory , HasImage,HasCreatedAt;
}
