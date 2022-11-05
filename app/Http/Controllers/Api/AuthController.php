<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Manager;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registerManager']]);
    }

    public function registerManager(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',            
        ]);

        if ($validator->fails()) {
            Log::info($validator->errors());
            return response()->json($validator->errors(), 422);
        }

        $manager = new Manager();

        if($request->hasFile('image')) {
            $im_extension = $request->file('image')->extension();
            $manager
                ->addMediaFromRequest('image')
                ->usingFileName(fake()->uuid().'.'.$im_extension)
                ->toMediaCollection()
                ->useDisk('s3');
        }
        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->username = $request->username;
        $manager->password = bcrypt($request->password);
        $manager->is_manager = true;
        $manager->is_employee = false;
        $manager->save();

        $managerUserModel = User::find($manager->id);

        $token = JWTAuth::fromUser($manager);

        return response()->json(compact('manager', 'token'), 201);
    }

    public function registerEmployee(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',  
        ]);

        if ($validator->fails()) {
            Log::info($validator->errors());
            return response()->json($validator->errors(), 422);
        }

        $manager = Manager::find(Auth::id());
        $restaurant = $manager->restaurant;

        $employee = new Employee();
        $im_extension = $request->file('image')->extension();
        $employee
            ->addMediaFromRequest('image')
            ->usingFileName(fake()->uuid().'.'.$im_extension)
            ->toMediaCollection()
            ->useDisk('s3');
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->username = $request->username;
        $employee->password = bcrypt($request->password);
        $employee->restaurant()->associate($restaurant);
        $employee->is_manager = false;
        $employee->is_employee = true;
        $employee->save();

        $user = User::find($employee->id);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('employee', 'token'), 201);
    }

    public function addCustomer()
    {
        $customer = new Customer();
        $customer->name = 'CUSTOMER'; //TODO: add customer name
        $username = Str::random(16);
        $customer->username = $username;
        $customer->email = Str::random(10) . '@gmail.com';
        $random_password = Str::random(32);
        $customer->password = bcrypt($random_password);
        $customer->is_manager = false;
        $customer->is_employee = false;

        $manager = Manager::find(Auth::id());
        $restaurant = $manager->restaurant;
        
        $customer->restaurant()->associate($restaurant);
        $customer->save();
        
        $user = User::find($customer->id);

        $token = JWTAuth::fromUser($user);

        $customerLogin = [
            'username' => $username,
            'password' => $random_password,
        ];

        return response()->json(compact('user', 'customerLogin', 'token'), 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }

        if (! $token = JWTAuth::attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED); // 401
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return UserResource
     */
    public function me()
    {
        return new UserResource( auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        //    'user' => new UserResource(auth()->user())
        ]);
    }
}