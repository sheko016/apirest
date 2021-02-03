<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();
        return response()->json(['Data' =>  $usuarios], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $reglas);

        $campos = $request->all();       
        
        $campos["password"] = bcrypt($request->password);
        $campos["verified"] = User::USUARIO_NO_VERIFICADO;
        $campos["verification_token"] = User::GenerarVerificacionToken();
        $campos["admin"] = User::USUARIO_REGULAR;
        

        $usuarios = User::create($campos);


        return response()->json(['data' => $usuarios], 201);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //findOrFail ->  Sirve para indicar si un usuario no existe da error 404
        $usuarios = User::findOrFail($id);
        return response()->json(['data' => $usuarios], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $usuarios = User::findOrFail($id);
        

        /*
        'email' => 'required|email|unique:users,email' . $usuarios->id,
            De esta forma le indico que se va a verificar solamente el email que viene por parametro y no el email que ya tiene el usuarios

        */
         $reglas = [
            'email' => 'email|unique:users,email,' . $usuarios->id,
            'password' => 'min:6|confirmed',
           // 'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ];

        $this->validate($request, $reglas);

        //request->has('name') = me permite verificar si viene campos por name 
        if ($request->has('name')) {
            $usuarios->name = $request->name;
        }

        //dd($usuarios);


        if ($request->has('email') && $usuarios->email != $request->email) {
                    $usuarios->verified = User::USUARIO_NO_VERIFICADO;
                    $usuarios->verification_token = User::GenerarVerificacionToken();
                    $usuarios->email = $request->email;    
        }

        if ($request->has('password')) {
            $usuarios->password = bcrypt($request->password);

        }


        if ($request->has('admin')) {
            
            if (!$usuarios->EsVerificado()) {
                return response()->json(['error' => 'Unicamente los usuarios verificados pueden cambiar su rol a administrador' , 'code' => 409], 409);
            }
            
            $usuarios->admin = $request->admin;
        }
        

        //!$usuarios->isDitry() me permite verificar si en verdad han pasado datos par aactualizar el usuarios
        if (!$usuarios->isDirty()) {
            return response()->json(['error' => 'Se debe especificar al meos un valor diferente para actualizar' , 'code' => 422], 422);
        }
        

        $usuarios->save();

        return response()->json(['Data' =>  $usuarios], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuarios = User::findOrFail($id);
        $usuarios->delete();

        return response()->json(['Data' =>  $usuarios], 200);
    }
}
