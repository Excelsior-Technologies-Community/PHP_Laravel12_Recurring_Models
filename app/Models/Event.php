<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;
use MohammedManssour\LaravelRecurringModels\Contracts\Repeatable as RepeatableContract;
use MohammedManssour\LaravelRecurringModels\Concerns\Repeatable;
use MohammedManssour\LaravelRecurringModels\Enums\RepetitionType;

class Event extends Model implements RepeatableContract
{
    use Repeatable;

    protected $fillable = ['title', 'description', 'type'];

    public function repetitionBaseDate(?RepetitionType $type = null): CarbonInterface
    {
        return $this->created_at;
    }
}