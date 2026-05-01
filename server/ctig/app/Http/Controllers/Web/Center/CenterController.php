<?php

namespace App\Http\Controllers\Web\Center;

use App\Http\Requests\Center\CenterUpdateRequest;
use App\Http\Resources\CenterResource;
use App\Models\Center;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CenterController
{
    public function index(){
        //Все организации. Можно переиспользовать для info и для sa
    }
    public function store(){
        //Создать, а потом еще orgadmin
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
        $center->update($payload);
        return response()->json($center);
    }
    
    public function show(Request $request, Center $center){
        if($center->id != $request->user()->center_id){
            abort(404);
        }
        return Inertia::render('Center/Center', [
            'data' => new CenterResource($center),
            'tab' => 'data'
        ]);
    }

    public function destroy(Center $center){
        $center->is_active = false;
        return response()->noContent();
    }
}
