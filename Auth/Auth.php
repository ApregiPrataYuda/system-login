//controller name-file (Auth.php)
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

  public function login()
  {
    check_already_login();
    $this->load->view('Login');
  }

  function process()
  {
    $post = $this->input->post(null, true);
    if (isset($post['login'])) {
      $this->load->model('User_m');
      $query = $this->User_m->login($post);
?>
      <link href="<?= base_url() ?>assets/backend/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet">
      <script src="<?= base_url() ?>assets/backend/vendor/sweetalert2/sweetalert2.min.js"></script>
      <style>
        body {
          font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
          font-size: 1.124em;
          font-weight: normal;
        }
      </style>

      <body></body>
      <?php
      if ($query->num_rows() > 0) {
        $row = $query->row();
        $params = array(
          'user_id' => $row->user_id,
          'level'  => $row->level,
        );
        $this->session->set_userdata($params);
      ?>
        <script>
          Swal.fire({
            icon: 'success',
            title: 'success',
            text: 'selamat, login berhasil?'
          }).then((result) => {
            window.location = '<?= site_url('Dashboard') ?>';
          })
        </script>
      <?php
      } else {
      ?>
        <script>
          Swal.fire({
            icon: 'info',
            title: 'LOGIN GAGAL',
            text: 'Login, Gagal username dan password salah?'
          }).then((result) => {
            window.location = '<?= site_url('Auth/login') ?>';
          })
        </script>
<?php
      }
    }
  }

  public function logout()
  {
    $keluar = array('user_id', 'level');
    $this->session->unset_userdata($keluar);
    redirect('Auth/login');
  }
}



//modell name-file(User_m.php)

<?php

class User_m extends CI_model
{
  public function login($post)
  {
    $this->db->select('*');
    $this->db->from('user');
    $this->db->where('username', $post['username']);
    $this->db->where('password', sha1($post['password']));
    $query = $this->db->get();
    return $query;
  }

  public function get($id = null)
  {
    $this->db->from('user');
    if ($id != null) {
      $this->db->where('user_id', $id);
    }
    $query = $this->db->get();
    return $query;
  }


  public function delete($id)
  {
    $this->db->where('user_id', $id);
    $this->db->delete('user');
  }

  public function add($post)
  {
    $params['name'] = $post['name'];
    $params['username'] = $post['username'];
    $params['password'] = sha1($post['password']);
    $params['address'] = $post['address'] != "" ? $post['address'] : null;
    $params['level'] = $post['level'];
    $this->db->insert('user', $params);
  }

  public function edit($post)
  {
    $params['name'] = $post['name'];
    $params['username'] = $post['username'];
    if (!empty($post['password'])) {

      $params['password'] = sha1($post['password']);
    }
    $params['address'] = $post['address'] != "" ? $post['address'] : null;
    $params['level'] = $post['level'];
    $this->db->where('user_id', $post['user_id']);
    $this->db->update('user', $params);
  }
}


//viewss nama-file(Login.php)

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?=base_url()?>assets/backend/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url()?>assets/backend/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?=base_url()?>assets/backend/dist/css/AdminLTE.min.css">
</head>
<body class="hold-transition login-page bg-purple">
<div class="login-box">
  <div class="login-logo">
    <a href=""><b>Login-</b>system</a>
  </div>
  <div class="login-box-body">
    <p class="login-box-msg">lakukan login terlebih dahulu</p>
    <?php $this->load->view('flash_messages');?>
    <form action="<?=site_url('Auth/proses')?>" method="post">
    <div class="form-group has-feedback">
        <input type="text" name="username" class="form-control" placeholder="username" required autofocus>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
      <div class="row">
        <div class="col-xs-8"></div>
        <div class="col-xs-4">
          <button type="submit" name="login" class="btn btn-primary btn-block btn-sm">Sign In</button>
        </div>
      </div>
    </form>
  </div>
 </div>
<script src="<?=base_url()?>assets/backend/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?=base_url()?>assets/backend/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>


