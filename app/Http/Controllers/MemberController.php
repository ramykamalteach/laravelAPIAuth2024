<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Member;

use App\Services\JwtService;

class MemberController extends Controller
{
    private $jwtService;
    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }
    //
    public function register(Request $request) {
        $request->validate([
            'loginName' => 'required',
            'password' => 'required',
            'fullName' => 'required',
        ]);

        $member = new Member();
        $member->password = Hash::make($request->password);
        $member->loginName = $request->loginName;
        $member->fullName = $request->fullName;
    
        $member->save();

        return response()->json(['message' => 'Member registered']);
    }

    public function login(Request $request) {
        $request->validate([
            'loginName' => 'required',
            'password' => 'required',
        ]);

        $loginUser = Member::where("loginName", "=", $request->loginName)->first();

        if($loginUser){
            if (Hash::check($request->password, $loginUser->password)) {
                
                $token = $this->jwtService->createToken([
                    'user_id' => $loginUser->id,
                    'fullName' => $loginUser->fullName,
                ]);

                $loginUser->loginToken = $token->toString();
                $loginUser->save();
        
                return response()->json(['token' => $token->toString()]);

            }
            else {
                return response()->json(['message' => 'Login Failed']);
            }
        }
        else {
            return response()->json(['message' => 'Login Failed']);
        }
    }
}
