<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Interfaces\Sortable;
use App\Traits\SortableTrait;

class Todo extends Model implements Sortable
{
    use SortableTrait;

    protected $fillable = ['title', 'description', 'status', 'due_date', 'notes', 'priority', 'my_day', 'sheet_id'];

    protected $hidden = ['sheet', 'client'];

    protected $appends = ['is_overdue', 'is_myday', 'sheet_name'];

    protected $sortable = [
        'order_column_name' => 'priority',
        'sort_when_creating' => true
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    const PENDING = 0;
    const DONE = 1;

    public function sheet()
    {
        return $this->belongsTo('App\Sheet');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSheetNameAttribute()
    {
        if ($this->attributes['sheet_id'])
        {
            return $this->sheet->name;
        }

        return 'Tasks';
    }

    public function getIsOverdueAttribute()
    {
        $is_overdue = false;
        if ($this->due_date) {
            $today = Carbon::now()->startOfDay();

            $due_date = Carbon::parse($this->due_date);
            $diff = $due_date->diffInDays($today, false);

            $is_overdue = $diff > 0;
        }
        return $this->is_overdue = $is_overdue;
    }

    public function getIsMyDayAttribute()
    {
        $is_myday = false;
        if ($this->my_day) {
            $today = Carbon::now()->startOfDay();
            $my_day = Carbon::parse($this->my_day);
            $diff = $my_day->diffInDays($today, false);

            $is_myday = $diff == 0;
        }
        return $this->is_myday = $is_myday;
    }
}
