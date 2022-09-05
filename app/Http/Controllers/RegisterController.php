<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

   

class RegisterController extends BaseController
{

    /**
     * Register api
     * @return \Illuminate\Http\Respose 
     */
    public function register(Request $r){
        $validator = Validator::make( $r->all() , [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ] ); 

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $r->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('mitoken')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }
    


    /**
     * Login api 
     * 
     * @return \Illuminate\Http\Response
     */
    public function Login(Request $r){
        if(Auth::attempt(['email' => $r->email, 'password' => $r->password])){ 
            $user = Auth::user();
            $success['token'] = $user->createToken('mitoken')->plainTextToken; 
            $success['name'] =  $user->name;
            //return ($user instanceof User);
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    
    
}
