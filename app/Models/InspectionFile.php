<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InspectionFile
 * 
 * @property int $id
 * @property int $owner_id
 * @property int $vehicle_id
 * @property string $filename
 * @property Carbon $uploaded_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Vehicle $vehicle
 *
 * @package App\Models
 */
class InspectionFile extends Model
{
	protected $table = 'inspection_files';

	protected $casts = [
		'owner_id' => 'int',
		'vehicle_id' => 'int',
		'uploaded_at' => 'datetime'
	];

	protected $fillable = [
		'owner_id',
		'vehicle_id',
		'filename',
		'uploaded_at'
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
