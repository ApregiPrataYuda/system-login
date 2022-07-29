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
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Login</title>

  <!-- Custom fonts for this template-->
  <link href="<?= base_url() ?>assets/backend/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?= base_url() ?>assets/backend/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-success">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6"><img src="<?= base_url() ?>assets/backend/img/inventory.png" alt=""></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">selamat Datang Inventory App!</h1>

                  </div>
                  <form action="<?= site_url('Auth/process') ?>" method="POST">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Enter username Address..." required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password" required>
                    </div>

                    <button type="submit" name="login" class="btn btn-outline-success btn-block btn-sm">Sign In</button>
                  </form>
                  <hr>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url() ?>assets/backend/vendor/jquery/jquery.min.js"></script>
  <script src="<?= base_url() ?>assets/backend/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?= base_url() ?>assets/backend/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?= base_url() ?>assets/backend/js/sb-admin-2.min.js"></script>

</body>

</html>


