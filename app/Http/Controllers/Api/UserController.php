<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private User $user;

    public function  __construct(User $user) {
        $this->user = $user;
    }

    public function index()
    {
        //$users = $this->user::all()->pluck('name', 'id')->toArray();
        
        $users = $this->user::select('email', 'id')->get();

        return response()->json(["Registered users" => $users], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $photoName = $this->uploadPhoto($request);

            $users = $this->user::create([
                "name" =>$request->name,
                "email" => $request->email,
                "password" =>  $request->password,
                "photo" => $photoName,
                "phone" => $request->phone
            ]);

            $users->save();

            return response()->json(["Msg" => "Record created successfully"], 201);

        } catch (Exception $e) {
            \Log::info($e->getMessage());
            return response()->json(["Error" => $e->getMessage()], 400);
        
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$user = $this->user::where('id', $id)->pluck(['name', 'email']);

        $user = $this->user::select('name', 'email', 'phone', 'photo')
                    ->where('id', $id)
                    ->get();

        return response()->json(["User data" => $user], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    
        $data = $request->all();
        $data["photo"] =  $this->uploadPhoto($request);
        
        $this->user::whereId($id)->update($data);

        
        return response()->json(["Msg" => "Updated user"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->user::where('id', $id)->delete();

        return response()->json(["Msg" => "Deleted user"], 200);
    }

    private function uploadPhoto($request) {
        $imagem_valida = $request->hasFile('photo') && $request->file('photo')->isValid();

        if ($imagem_valida)
        {
            $nome = uniqid(date('HisYmd'));

            // upload da foto da prova
            $extensao = $request->photo->extension();
            $fotoNome = "{$nome}.{$extensao}";
            $upload = $request->photo->storeAs(
                '/public/photos', $fotoNome
            );
            
            return  asset("/storage/photos/".$fotoNome);

            
        } else {
            throw new Exception('Upload error');
        }
    }
}
