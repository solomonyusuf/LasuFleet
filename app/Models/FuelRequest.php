<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FuelRequest
 * 
 * @property int $id
 * @property int $owner_id
 * @property int $vehicle_id
 * @property int $litres
 * @property string $reason
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Vehicle $vehicle
 *
 * @package App\Models
 */
class FuelRequest extends Model
{
	protected $table = 'fuel_requests';

	protected $casts = [
		'owner_id' => 'int',
		'vehicle_id' => 'int',
		'litres' => 'int'
	];

	protected $fillable = [
		'owner_id',
		'vehicle_id',
		'litres',
		'reason',
		'status'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'owner_id');
	}

	public function vehicle()
	{
		return $this->belongsTo(Vehicle::class);
	}
}
