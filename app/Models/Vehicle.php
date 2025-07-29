<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vehicle
 * 
 * @property int $id
 * @property string $plate_number
 * @property Carbon $registration_date
 * @property string $model
 * @property string $color
 * @property string $condition
 * @property string $status
 * @property int $owner_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|ConditionUpdate[] $condition_updates
 * @property Collection|FuelRequest[] $fuel_requests
 * @property Collection|InspectionFile[] $inspection_files
 * @property Collection|MaintenanceRequest[] $maintenance_requests
 *
 * @package App\Models
 */
class Vehicle extends Model
{
	protected $table = 'vehicles';

	protected $casts = [
		'registration_date' => 'datetime',
		'owner_id' => 'int'
	];

	protected $fillable = [
		'plate_number',
		'registration_date',
		'model',
		'color',
		'condition',
		'status',
		'owner_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'owner_id');
	}

	public function condition_updates()
	{
		return $this->hasMany(ConditionUpdate::class);
	}

	public function fuel_requests()
	{
		return $this->hasMany(FuelRequest::class);
	}

	public function inspection_files()
	{
		return $this->hasMany(InspectionFile::class);
	}

	public function maintenance_requests()
	{
		return $this->hasMany(MaintenanceRequest::class);
	}
}
