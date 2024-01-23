<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AdditionService
 *
 * @property-read mixed $name
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionService active()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionService query()
 */
	class AdditionService extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $email_verified_at
 * @property string|null $password
 * @property string|null $image
 * @property string $is_active
 * @property string $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $avatar
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin defaultOrdered()
 * @method static \Database\Factories\AdminFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin onlyAdmins()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin onlyEmployees()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin onlyIsActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin withoutRole($roles, $guard = null)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CarMake
 *
 * @property int $id
 * @property string|null $name_en
 * @property string|null $name_ar
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Driver> $drivers
 * @property-read int|null $drivers_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CarModel> $models
 * @property-read int|null $models_count
 * @method static \Illuminate\Database\Eloquent\Builder|CarMake defaultOrdered()
 * @method static \Database\Factories\CarMakeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CarMake newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarMake newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarMake query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarMake whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarMake whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarMake whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarMake whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarMake whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarMake whereUpdatedAt($value)
 */
	class CarMake extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\CarModel
 *
 * @property int $id
 * @property string|null $name_en
 * @property string|null $name_ar
 * @property int|null $make_id
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Driver> $drivers
 * @property-read int|null $drivers_count
 * @property-read \App\Models\CarMake|null $make
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel defaultOrdered()
 * @method static \Database\Factories\CarModelFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereMakeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereUpdatedAt($value)
 */
	class CarModel extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\CarSize
 *
 * @property int $id
 * @property string|null $name_en
 * @property string|null $name_ar
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Driver> $drivers
 * @property-read int|null $drivers_count
 * @method static \Database\Factories\CarSizeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize whereUpdatedAt($value)
 */
	class CarSize extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ChFavorite
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChFavorite query()
 */
	class ChFavorite extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ChMessage
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChMessage query()
 */
	class ChMessage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property int $id
 * @property string|null $name_en
 * @property string|null $name_ar
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int|null $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|City defaultOrdered()
 * @method static \Database\Factories\CityFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ContactUs
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactUs query()
 */
	class ContactUs extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $name_en
 * @property string|null $name_ar
 * @property string|null $iso3
 * @property string|null $numeric_code
 * @property string|null $iso2
 * @property string|null $phonecode
 * @property string|null $capital
 * @property string|null $currency
 * @property string|null $currency_name
 * @property string|null $currency_symbol
 * @property string|null $nationality
 * @property string|null $latitude
 * @property string|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\City> $cities
 * @property-read int|null $cities_count
 * @method static \Illuminate\Database\Eloquent\Builder|Country defaultOrdered()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCapital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCurrencyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCurrencySymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIso2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIso3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereNumericCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country wherePhonecode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Coupon
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon active()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 */
	class Coupon extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Driver
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int|null $country_id
 * @property int|null $city_id
 * @property string|null $email
 * @property string $phone
 * @property int $is_verified
 * @property int $is_listing_to_orders_now هو الان في حاله انتظار الطلبات
 * @property int $can_receive_orders استقبال الطلبات
 * @property string|null $verification_code
 * @property string|null $birth_date
 * @property string|null $id_number رقم الهوية / الاقامة
 * @property float|null $deduction_percentage نسبة الاستقطاع ضعها سالب واحد لاستخدام القيمه الافتراضيه في الاعدادت
 * @property int|null $size_id
 * @property int|null $make_id ماركة السيارة
 * @property int|null $model_id موديل السيارة
 * @property int|null $manufacturing_year سنه الصنع
 * @property string|null $plate_letters حروف اللوحة
 * @property string|null $plate_numbers رقم اللوحة
 * @property string|null $car_color لون السيارة
 * @property string|null $car_max_capacity السعة القصوى للمركبة
 * @property string|null $car_id_number الرقم التسلسلي للمركبة
 * @property int|null $has_traffic_tickets هل لديك مخالفات مروريه
 * @property string|null $invitation_code
 * @property string|null $banned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Cog\Laravel\Ban\Models\Ban> $banHistories
 * @property-read int|null $ban_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Cog\Laravel\Ban\Models\Ban> $bans
 * @property-read int|null $bans_count
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\CarMake|null $make
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\CarModel|null $model
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Driver> $receivedInvitationCodes
 * @property-read int|null $received_invitation_codes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Driver> $sentInvitationCodes
 * @property-read int|null $sent_invitation_codes_count
 * @property-read \App\Models\CarSize|null $size
 * @method static \Illuminate\Database\Eloquent\Builder|Driver defaultOrdered()
 * @method static \Database\Factories\DriverFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver onlyCanReceiveOrders()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver onlyHasTrafficTickets()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver onlyIsVerified()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver onlyListingToOrders()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver query()
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCanReceiveOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCarColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCarIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCarMaxCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereDeductionPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereHasTrafficTickets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereInvitationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereIsListingToOrdersNow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereMakeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereManufacturingYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver wherePlateLetters($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver wherePlateNumbers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereVerificationCode($value)
 */
	class Driver extends \Eloquent implements \Spatie\MediaLibrary\HasMedia, \Cog\Contracts\Ban\Bannable {}
}

namespace App\Models{
/**
 * App\Models\Links
 *
 * @property-read mixed $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Links> $sub_links
 * @property-read int|null $sub_links_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Links> $sub_links_permitions
 * @property-read int|null $sub_links_permitions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Links newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Links newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Links query()
 */
	class Links extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Media
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property mixed $manipulations
 * @property mixed $custom_properties
 * @property mixed $generated_conversions
 * @property mixed $responsive_images
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereConversionsDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereGeneratedConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereManipulations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereResponsiveImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUuid($value)
 */
	class Media extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property int $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Notification defaultOrdered()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Onbording
 *
 * @property-read mixed $desc
 * @property-read mixed $title
 * @method static \Illuminate\Database\Eloquent\Builder|Onbording active()
 * @method static \Illuminate\Database\Eloquent\Builder|Onbording newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Onbording newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Onbording query()
 */
	class Onbording extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property-read \App\Models\UserAddress|null $address
 * @property-read mixed $discount_price
 * @property-read mixed $from_location_info
 * @property-read mixed $payment_method_name
 * @property-read mixed $status_name
 * @property-read mixed $sub_total
 * @property-read mixed $to_location_info
 * @property-read mixed $total
 * @property-read mixed $total_profit
 * @property-read mixed $travel_type_name
 * @property-read mixed $vat_price
 * @property-read \App\Models\Transaction|null $transaction
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TravelTraking> $travel_traking
 * @property-read int|null $travel_traking_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Reason
 *
 * @property-read mixed $title
 * @method static \Illuminate\Database\Eloquent\Builder|Reason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reason query()
 */
	class Reason extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Slider
 *
 * @property-read mixed $desc
 * @property-read mixed $title
 * @method static \Illuminate\Database\Eloquent\Builder|Slider active()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider query()
 */
	class Slider extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property-read mixed $type_name
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TravelTraking
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TravelTraking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelTraking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelTraking query()
 */
	class TravelTraking extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserAddress
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAddress query()
 */
	class UserAddress extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserNotifacation
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotifacation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotifacation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNotifacation query()
 */
	class UserNotifacation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserRate
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRate query()
 */
	class UserRate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UsersCard
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UsersCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersCard query()
 */
	class UsersCard extends \Eloquent {}
}

