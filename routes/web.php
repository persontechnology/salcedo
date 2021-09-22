<?php

use App\Models\Categoria;
use App\Models\Comentario;
use App\Models\Geo;
use App\Models\Reservacion;
use App\Models\Turismo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('crudbooster:install');
    return 'ok';
    // Artisan::call('storage:link');
    // Artisan::call('key:generate');
    // Artisan::call('migrate:fresh --seed');
});

Route::get('/', function () {
    return view('welcome');
});


// API REST JSON SALCEDO
Route::prefix('app')->group(function () {
    
    // OBTENER LAS CATEGORIAS
    Route::get('/categorias', function () {
        $categorias=Categoria::all();
        $data = [];
        foreach ($categorias as $cat) {
            array_push($data, [
                'id'=>$cat->id,
                'nombre'=>$cat->nombre,
                'descripcion'=>$cat->descripcion,
                'foto'=>url('/'.$cat->foto)
            ]);
        }
        return response()->json($data);
    });

    // OBTENER LAS TURISMO X ID CATEGORIA
    Route::get('/turismos/{id}', function ($id) {
        $turismos=Turismo::where('categoria_id',$id)->get();
        
        $data = [];
        foreach ($turismos as $tur) {
            array_push($data, [
                'id'=>$tur->id,
                'nombre'=>$tur->nombre,
                'direccion'=>$tur->direccion,
                'telefono'=>$tur->telefono,
                'sitioweb'=>$tur->sitioweb,
                'foto'=>url('/'.$tur->foto)
            ]);
        }
        return response()->json($data);
    });

    // OBTENER DETALLE DE TURISMO
    
    Route::get('/turismos-detalle/{id}', function ($id) {
         $tur=Turismo::find($id);
        $data=array(
            'id'=>$tur->id,
            'nombre'=>$tur->nombre,
            'direccion'=>$tur->direccion,
            'telefono'=>$tur->telefono,
            'sitioweb'=>$tur->sitioweb,
            'foto'=>url('/'.$tur->foto),
            'galerias'=>$tur->galerias,
            'detalle'=>$tur->detalle,
            'latitud'=>$tur->latitud,
            'longitud'=>$tur->longitud,
            'user'=>$tur->user,
            'comentarios'=>$tur->comentarios->count()
        );

        return $data;
    });

    Route::get('/comentarios/{id}', function ($id) {
        $tur=Turismo::find($id);
        $data = array();
        foreach ($tur->comentarios as $com) {
            array_push($data,[
                
                'comentario'=>$com->comentario,
                'fecha'=>$com->created_at?$com->created_at->diffForHumans():'',
                'user'=>[
                    'name'=>$com->user->nombres?$com->user->nombres.' '.$com->user->apellidos:$com->user->name,
                    'foto'=>$com->user->photo?url('/'.$com->user->photo):substr($com->user->name,0,2)
                ]
            ]);
        }
        return $data;
   });

   Route::get('/enviar-comentario/{idTur}/{idUser}/{Come}', function ($idTur,$idUser,$Come) {
        $com=new Comentario();
        $com->comentario=$Come;
        $com->turismo_id=$idTur;
        $com->user_id=User::where('email',$idUser)->first()->id;
        $com->save();

        $data = array(
            'comentario'=>$com->comentario,
            'fecha'=>$com->created_at?$com->created_at->diffForHumans():'',
            'user'=>[
                'name'=>$com->user->nombres?$com->user->nombres.' '.$com->user->apellidos:$com->user->name,
                'foto'=>$com->user->photo?url('/'.$com->user->photo):substr($com->user->name,0,2)
            ]
        );
        return $data;
    });

    Route::get('/login/{email}/{password}',function($email,$password){
        $res=User::where('email',$email)->first();
        if (Hash::check($password, $res->password)) {
            return json_encode('y');
        }
        return json_encode('n');
    });


    Route::get('/obtener-usuario/{email}',function($email){
        return User::where('email',$email)->first();
    });

    Route::get('/enviar-reservacion/{email}/{tur}/{desde}/{hasta}/{cantidad}/{cedula}/{nombres}/{apellidos}/{telefono}',
    function($email,$tur,$desde,$hasta,$cantidad,$cedula,$nombres,$apellidos,$telefono){
        $user=User::where('email',$email)->first();
        
        $user->cedula=$cedula;
        $user->nombres=$nombres;
        $user->apellidos=$apellidos;
        $user->telefono=$telefono;
        $user->save();
        
        $turi=Turismo::find($tur);
        
        $res=new Reservacion();
        $res->fecha_inicio=$desde;
        $res->fecha_final=$hasta;
        $res->cantidad_personas=$cantidad;
        $res->estado=false;
        $res->turismo_id=$turi->id;
        $res->user_id=$user->id;
        $res->save();
        return json_encode('success');
    });

    Route::get('/mis-reservaciones/{email}',function($email){
        $user=User::where('email',$email)->first();
        $res=$user->turismosReservados()->orderBy('reservacions.id', 'desc')->get();
        
        $data = [];
        foreach ($res as $tur) {
            array_push($data, [
                'id'=>$tur->id,
                'nombre'=>$tur->nombre,
                'direccion'=>$tur->direccion,
                'telefono'=>$tur->telefono,
                'sitioweb'=>$tur->sitioweb,
                'foto'=>url('/'.$tur->foto),
                'fecha'=>$tur->pivot->created_at->diffForHumans(),
                'idR'=>$tur->pivot->id
            ]);
        }
        return response()->json($data);


    });
    
    Route::get('/eliminar-mi-reservacion/{id}',function($id){
        Reservacion::destroy($id);
        return json_encode('success');
    });

});

