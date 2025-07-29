<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MaintenanceRequest
 * 
 * @property int $id
 * @property int $owner_id
 * @property int $vehicle_id
 * @property string $issue
 * @property string $priority
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Vehicle $vehicle
 *
 * @package App\Models
 */
class MaintenanceRequest extends Model
{
	protected $table = 'maintenance_requests';

	protected $casts = [
		'owner_id' => 'int',
		'vehicle_id' => 'int'
	];

	protected $fillable = [
		'owner_id',
		'vehicle_id',
		'issue',
		'priority',
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
