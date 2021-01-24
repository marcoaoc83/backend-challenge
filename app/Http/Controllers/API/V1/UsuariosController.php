<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Usuarios\UsuarioRequest;
use App\Models\Usuario;
use Illuminate\Support\Facades\Http;

class UsuariosController extends BaseController
{

    protected $usuario = '';
    protected $api = 'https://api2.mlearn.mobi/integrator/qualifica/';
    protected $token = 'aSE1gIFBKbBqlQmZOOTxrpgPKgQkgshbLnt1NS3w';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Usuario $usuario)
    {
        $this->middleware('auth:api');
        $this->usuario = $usuario;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = $this->usuario->paginate(10);

        return $this->sendResponse($usuarios, 'Lista de Usuários');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Usuarios\UsuarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsuarioRequest $request)
    {
        try {

            $usuario = $this->usuario->create([
                'name' => $request->get('name'),
                'msisdn' => $request->get('msisdn'),
                'access_level' => $request->get('access_level'),
                'score' => $request->get('score'),
                'level' => $request->get('level'),
            ]);

            $response = Http::withHeaders(["service-id"=>"qualifica","app-users-group-id"=>"20"])
            ->withToken($this->token)
            ->post($this->api.'users', [
                'name' => $request->get('name'),
                'msisdn' => $request->get('msisdn'),
                'access_level' => $request->get('access_level'),
                'score' => $request->get('score'),
                'level' => $request->get('level'),
                'external_id' => $usuario->id,
            ]);
            $usuario->update(['id_mlearn'=>$response['data']['id']]);
            return $this->sendResponse($usuario, 'Usuario criado');
        }
        catch (\Exception $e)
        {
            return $this->sendError("Erro!.",$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = $this->usuario->findOrFail($id);

        return $this->sendResponse($usuario, 'Usuario Detalhes');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(UsuarioRequest $request, $id)
    {
        try {
            $usuario = $this->usuario->findOrFail($id);

            $usuario->update($request->all());
            $response = Http::withHeaders(["service-id"=>"qualifica","app-users-group-id"=>"20"])
                ->withToken($this->token)
                ->put($this->api.'users/'.$usuario->id_mlearn, [
                    'name' => $request->get('name'),
                    'msisdn' => $request->get('msisdn'),
                    'access_level' => $request->get('access_level'),
                    'score' => $request->get('score'),
                    'level' => $request->get('level'),
                    'external_id' => $usuario->id,
                ]);
            return $this->sendResponse($usuario, 'Usuario editado');
        }
        catch (\Exception $e)
        {
            return $this->sendError("Erro!.",$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            $this->authorize('isAdmin');

            $usuario = $this->usuario->findOrFail($id);
            $id_mlearn=$usuario->id_mlearn;

            $usuario->delete();

            Http::withHeaders(["service-id"=>"qualifica","app-users-group-id"=>"20"])
                ->withToken($this->token)
                ->delete($this->api.'users/'.$id_mlearn);
            return $this->sendResponse($usuario, 'Usuario deletado');
        }
        catch (\Exception $e)
        {
            return $this->sendError("Erro!.",$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function upgrade($id)
    {

        try {
            $usuario = $this->usuario->findOrFail($id);

            $usuario->update(['access_level'=>'premium']);
            $response = Http::withHeaders(["service-id"=>"qualifica","app-users-group-id"=>"20"])
                ->withToken($this->token)
                ->put($this->api.'users/'.$usuario->id_mlearn."/upgrade");
            return $this->sendResponse($usuario, 'Usuario editado');
        }
        catch (\Exception $e)
        {
            return $this->sendError("Erro!.",$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function downgrade($id)
    {

        try {
            $usuario = $this->usuario->findOrFail($id);

            $usuario->update(['access_level'=>'free']);
            $response = Http::withHeaders(["service-id"=>"qualifica","app-users-group-id"=>"20"])
                ->withToken($this->token)
                ->put($this->api.'users/'.$usuario->id_mlearn."/downgrade");
            return $this->sendResponse($usuario, 'Usuario editado');
        }
        catch (\Exception $e)
        {
            return $this->sendError("Erro!.",$e->getMessage());
        }
    }
}
