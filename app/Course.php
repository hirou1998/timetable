<?php

namespace App;

class Course extends Model
{
    //protected $appends = ['periodsCount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function periods()
    {
        return $this->hasMany(Period::class)->orderBy('day_of_week', 'ASC');
    }

    public function getPeriodsCountAttribute()
    {
        return $this->periods->count();
    }

    public function updatePeriod($course, $request)
    {
        $periodsCount = $course->getPeriodsCountAttribute();
        $requestPeriodCount = count($request->periods);

        if($periodsCount === $requestPeriodCount){
            $this->updatePeriodExecution($course, $request, $requestPeriodCount);
        }else{
            $this->updatePeriodExecution($course, $request, $requestPeriodCount);

            $added = array();
            for($i = 0; $i < $requestPeriodCount; $i++){
                if(!array_key_exists('id', $request->periods[$i])){
                    array_push($added, [
                        'day_of_week' => $request->periods[$i]['day_of_week'],
                        'period' => $request->periods[$i]['period'],
                    ]);
                }
            }
            
            foreach($added as $add){
                $course->periods()->create([
                    'day_of_week' => $add['day_of_week'],
                    'period' => $add['period'],
                    'user_id' => auth()->user()->id,
                ]);
            }
        }
    }

    public function updatePeriodExecution($course, $request, $requestPeriodCount)
    {
        foreach ($course->periods as $period){

            for($i = 0; $i < $requestPeriodCount; $i++){
                if(!array_key_exists('id', $request->periods[$i])){
                    continue;
                }
                if($request->periods[$i]['id'] === $period->id){
                    $day = $request->periods[$i]['day_of_week'];
                    $p = $request->periods[$i]['period'];
                }
            }
            
            $period->update([
                'day_of_week' => $day,
                'period' => $p,
            ]);
        }
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
