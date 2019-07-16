<?php
namespace App;
use App\Notifications\SMSNotification;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Table Name
    protected $table = 'posts';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
   
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopePostsDue($query)
    {
        $now = Carbon::now();
        $inTenMinutes = Carbon::now()->addMinutes(10);
        return $query->where('notificationTime', '>=', $now)->where('notificationTime', '<=', $inTenMinutes);
    }

}
