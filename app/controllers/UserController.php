<?php

class UserController extends \BaseController {

    /**
     * Control admin credintials for modification User
     *
     */
    public function __construct() {
        $this->beforeFilter('admin', array('only' => array('store', 'update', 'destroy')));
    }
    
    /**
     * Display a listing of the User.
     *
     * @param int $_GET['page'] 
     * @param int $_GET['limit']      
     * @param int $_GET['sort_field'] 
     * @param int $_GET['sort_order'] 
     * @param int $_GET['trash']
     * @param int $_GET['role']
     * 
     * @return Response
     */
    public function index() {
        $rules = array('page' => 'integer|min:1',
            'limit' => 'integer|min:1',
            'sort_field' => 'in:username,surname',
            'sort_order' => 'in:ASC,DESC',
            'trash' => 'integer|between:0,1',
            'role' => 'in:ADMIN,USER',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $page = Input::get('page', 1);
            $limit = Input::get('limit', 100);
            $sortField = Input::get('sort_field', 'username');
            $sortOrder = Input::get('sort_order', 'ASC');
            $trash = Input::get('trash', 0);
            $roles = !empty(Input::get('role')) ? array(Input::get('role')) : array(User::ROLE_ADMIN, User::ROLE_USER);
        } else {
            $page = 1;
            $limit = 100;
            $sortField = 'username';
            $sortOrder = 'ASC';
            $trash = 0;
            $roles = array(User::ROLE_ADMIN, User::ROLE_USER);
        }

        if ($trash) {
            $query = User::withTrash()
                    ->whereIn('role', $roles)
                    ->orderBy($sortField, $sortOrder)
                    ->skip($limit * ($page - 1))->take($limit);                    
        } else {
            $query = User::whereIn('role', $roles)
                    ->orderBy($sortField, $sortOrder)
                    ->skip($page)->take($limit);                    
        }

        $users = $this->getQueryResult($query, Config::get('querycache.user.list'), 'user.list');
        
        return $this->buildResponse($users);
    }

    /**
     * Store a newly created User in storage.
     *
     * @return Response
     */
    public function store() {
        if (User::validate()) {
            $user = new User();
            $user->username = Input::get('username');
            $password = PassGen::make();
            $user->password = Hash::make($password);
            $user->firstname = Input::get('firstname');
            $user->surname = Input::get('surname');
            $user->email = Input::get('email');
            $user->role = Input::get('role', User::ROLE_USER);
            $user->save();
        } else {
            $error = 'Not correct user data';
        }

        if (!isset($error) && isset($user)) {
            Mail::queue('emails.register', 
                    array('user' => $user, 'password' => $password), 
                    function($message) use ($user) {
                $message->to($user->email, $user->firstname . ' ' . $user->surname)
                        ->subject('Welcome!');
            });
            //if(Config::get('mail.pretend')) Log::info(View::make('emails.resetpass',array('user' => $user, 'password' => $password))->render());
            return $this->buildResponse($user->id);
        } else {
            return $this->buildResponse(null, $error);
        }
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     * @return Response
     */
    public function show($id) {
        $query = User::with('projects')
                ->where('id', $id);
               
        $user = $this->getQueryResult($query, Config::get('querycache.user.id'), 'user.id');
        
        return $this->buildResponse($user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int  $id
     * @param int $_GET['password_reset']
     * 
     * @return Response
     */
    public function update($id) {
        if (User::validate(null, false)) {
            $user = User::find($id);
            $user->username = Input::get('username', $user->username);

            if (Input::get('password_reset', 0)) {
                $password = PassGen::make();
                $user->password = Hash::make($password);
            }

            $user->firstname = Input::get('firstname', $user->firstname);
            $user->surname = Input::get('surname', $user->surname);
            $user->email = Input::get('email', $user->email);
            $user->role = Input::get('role', $user->role);
            $user->save();
        } else {
            $error = 'Not correct user data';
        }

        if (!isset($error) && isset($user)) {
            if (isset($password)) {
                Mail::queue('emails.resetpass', 
                        array('user' => $user, 'password' => $password), 
                        function($message) use ($user) {
                    $message->to($user->email, $user->firstname . ' ' . $user->surname)
                            ->subject('Reset password!');
                });
                //if(Config::get('mail.pretend')) Log::info(View::make('emails.resetpass',array('user' => $user, 'password' => $password))->render());
            }

            return $this->buildResponse($user);
        } else {
            return $this->buildResponse(null, $error);
        }
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $affected = User::destroy($id);
        return $this->buildResponse($affected);
    }

}
