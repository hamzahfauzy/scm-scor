<?php

namespace App\Controllers\Auth;

use App\Models\Scm\Kustomer;
use App\Models\Scm\Supplier;
use App\Models\User;
use CodeIgniter\Controller;
 
class LoginController extends Controller
{
    public function index()
    {
        helper(['form']);
        return view('auth/login');
    } 
 
    public function auth()
    {
        $session = session();
        $model = new User();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $data = $model->where('email', $email)->first();
        if($data){
            $pass = $data['password'];
            $verify_pass = password_verify($password, $pass);
            if($verify_pass){
                $ses_data = [
                    'id'       => $data['id'],
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'logged_in'     => TRUE,
                    'level' => (new Supplier)->where('user_id', $data['id'])->first() ? 'Supplier' : ((new Kustomer)->where('user_id', $data['id'])->first() ? 'Kustomer' : 'Admin')
                ];
                $session->set($ses_data);
                return redirect()->to('/dashboard');
            }else{
                $session->setFlashdata('msg', 'Wrong Password');
                return redirect()->to('/login');
            }
        }else{
            $session->setFlashdata('msg', 'Email not Found');
            return redirect()->to('/login');
        }
    }
 
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}