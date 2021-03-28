<?php


namespace App;


use App\Models\Activity;
use phpDocumentor\Reflection\Types\Static_;

trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {
        if (!auth()->user()) return;
        foreach (Static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    public function activity()
    {
        return $this->MorphMany(Activity::class, 'subject');
    }

    public function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth()->id(),
        ]);
    }

    public function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }

}
