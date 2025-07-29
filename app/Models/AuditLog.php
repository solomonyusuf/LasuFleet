<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuditLog
 * 
 * @property int $id
 * @property string $type
 * @property int $request_id
 * @property string $changed_by
 * @property Carbon $timestamp
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class AuditLog extends Model
{
	protected $table = 'audit_logs';

	protected $casts = [
		'request_id' => 'int',
		'timestamp' => 'datetime'
	];

	protected $fillable = [
		'type',
		'request_id',
		'changed_by',
		'timestamp'
	];
}
