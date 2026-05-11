<?php

namespace App\Http\Controllers\Web\Center;

use App\Enums\Event;
use App\Enums\Resource;
use App\Http\Requests\Center\CenterUpdateRequest;
use App\Http\Resources\Center\CenterIndexResource;
use App\Http\Resources\Center\CenterResource;
use App\Models\Center;
use App\Support\Log\LogActivity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CenterController
{
    public function index(){
        return  CenterIndexResource::collection(Center::all());
    }

    public function show(Request $request, Center $center){
        if($center->id != $request->user()->center_id){
            abort(404);
        }
        $this->log(Event::Access, [
            'center_id' => $center->id
        ]);
        return Inertia::render('Center/Center', [
            'data' => new CenterResource($center),
            'tab' => 'data'
        ]);
    }

    public function store(){
        
    }

    public function update(CenterUpdateRequest $request, Center $center){
        $data = $request->validated();

        $payload = [
            'name' => $data['name'],
            'director_fio' => $data['directorFio'],
            'certificates_issue_address' => $data['certificatesIssueAddress'],
            'ogrn' => $data['ogrn'],
            'inn' => $data['inn'],
            'address' => $data['address'],
            'name_genitive' => $data['nameGenitive'],
            'commission_chairman' => $data['commissionChairman'],
        ];
        $before = new CenterResource($center)->resolve();
        $center->update($payload);

        $this->log(Event::Updated, [
            'center_id' => $center->id,
            'changes' => [
                'before' => $before,
                'after' => new CenterResource($center)->resolve()
            ]
        ]);
        return response()->json($center);
    }
    
    public function destroy(Center $center){
        $center->is_active = false;
        return response()->noContent();
    }

    protected function log(Event $event, array $context){
        LogActivity::event(
            event:$event,
            resource: Resource::Center,
            context:$context
        );
    }
}
