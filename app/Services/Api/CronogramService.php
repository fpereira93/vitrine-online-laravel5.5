<?php

namespace App\Services\Api;

use Auth;
use DB;
use Carbon\Carbon;
use App\Models\Event;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;

class CronogramService extends BaseService
{
    public function create($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'Name' => 'required',
                'BeginDate' => 'required'
            ], true);

            if (array_key_exists('allDay', $params)){
                $params['allDay'] = $params['allDay'] ? 1 : 0;
            }

            $params['id'] = Auth::user()->id;
            $event = Event::firstOrCreate($params);

            return $data->setData($event)->setMessage('Evento registrado com sucesso');
        });
    }

    public function update($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'EventId' => 'required|exists:Events',
                'Name' => 'required',
                'BeginDate' => 'required'
            ], true);

            $eventUpdate = Event::find($params['EventId']);
            $eventUpdate->fill($params);
            $eventUpdate->save();

            return $data->setData($eventUpdate)->setMessage('Evento atualizado com sucesso');
        });
    }

    public function delete(int $id)
    {
        return $this->transaction(function() use ($id){

            if (!Event::destroy($id)){
                throw new NotAllowedDeletionException('Não foi possível apagar o evento.');
            }
            return new ServiceData(true); 
        });
    }

    public function all()
    {
        return $this->holdMistake(function(){
            return new ServiceData(Event::where('id', '=', Auth::user()->id)->get());
        });
    }

    public function allFromMonth($year, $month)
    {
        return $this->holdMistake(function() use($year, $month){

            $data = Event::whereMonth('BeginDate','=', $month)
                ->whereYear('BeginDate', '=', $year)
            ->get();

            return new ServiceData($data);
        });
    } 
    public function allFromYear($year)
    {
        return $this->holdMistake(function() use($year){
            return new ServiceData(Event::whereYear('BeginDate', '=', $year)->get());
        });
    }
    public function nextEvents()
    {
        return $this->holdMistake(function() {
            return new ServiceData(
                Event::where('id', '=', Auth::user()->id)
            ->whereDate('BeginDate', '>=', Carbon::today())->get());
        });
    }
}
