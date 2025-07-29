<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string|null $plate_number
 * @property string $role
 * @property string $status
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ConditionUpdate[] $condition_updates
 * @property Collection|FuelRequest[] $fuel_requests
 * @property Collection|InspectionFile[] $inspection_files
 * @property Collection|MaintenanceRequest[] $maintenance_requests
 * @property Collection|Vehicle[] $vehicles
 *
 * @package App\Models
 */

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'username',
		'plate_number',
		'role',
		'status',
		'email',
		'email_verified_at',
		'password',
		'remember_token'
	];

	public function condition_updates()
	{
		return $this->hasMany(ConditionUpdate::class, 'owner_id');
	}

	public function fuel_requests()
	{
		return $this->hasMany(FuelRequest::class, 'owner_id');
	}

	public function inspection_files()
	{
		return $this->hasMany(InspectionFile::class, 'owner_id');
	}

	public function maintenance_requests()
	{
		return $this->hasMany(MaintenanceRequest::class, 'owner_id');
	}

	public function vehicles()
	{
		return $this->hasMany(Vehicle::class, 'owner_id');
	}
}
