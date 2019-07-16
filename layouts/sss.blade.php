<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  @hasrole('admin')<title>Admin - Dashboard</title>@endhasrole
  @hasrole('staff')<title>Staff - Dashboard</title>@endhasrole
  @hasrole('security')<title>Security - Dashboard</title>@endhasrole

  <!-- Custom fonts for this template-->
  <link href=" {{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">

  <!-- SMS STUff -->
  <link rel="stylesheet" href="" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <img src="{{url('/cover_images/logo.png')}}" alt="logo" style="width:500px"/>

    <!-- Navbar Search -->
    @guest
        
    @else
    @hasrole('admin')
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" action="/search" method="get">
      <div class="input-group">
        <input type="search" name="search" class="form-control" placeholder="Search Name..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    @endhasrole

    @hasrole('security')
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" action="/search" method="get">
      <div class="input-group">
        <input type="search" name="search" class="form-control" placeholder="Search Name..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    @endhasrole
    @endguest

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
    @guest
        
      @else
      @hasrole('admin')
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">Settings</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}"
          data-toggle="modal" data-target="#logoutModal"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="fas fa-fw fa-table"></i>
          <span>Logout</span></a>
            
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
            </form>
        </div>
      </li>
      @endhasrole

      @hasrole('security')
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">Settings</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}"
          data-toggle="modal" data-target="#logoutModal"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="fas fa-fw fa-table"></i>
          <span>Logout</span></a>
            
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
            </form>
        </div>
      </li>
      @endhasrole
      @endguest
    </ul>
  </div>
  </nav>
  

  <div id="wrapper">

    <!-- Sidebar -->
      @guest
    
    @else
    <ul class="sidebar navbar-nav">
    @hasrole('admin')<li class="nav-item active">
        <a class="nav-link" href="/admin/posts">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>@endhasrole
      @hasrole('admin')<li class="nav-item active">
        <a class="nav-link" href="/register">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Register User</span>
        </a>
      </li>@endhasrole
      @hasrole('staff')<li class="nav-item active">
        <a class="nav-link" href="/posts">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>@endhasrole
      @hasrole('security')<li class="nav-item active">
        <a class="nav-link" href="/security/posts">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>@endhasrole
      @hasrole('admin')<li class="nav-item active">
        <a class="nav-link" href="/admin/users/">
          <i class="fas fa-fw fa-cloud"></i>
          <span>Manage Users</span>
        </a>
      </li>@endhasrole
      @hasrole('staff')
      <li class="nav-item active">
      <a class="nav-link" href="/posts/create">
          <i class="fas fa-fw fa-globe"></i>
          <span>New Visitor</span></a>
      </li>
      @endhasrole
      @hasrole('staff')
      <li class="nav-item active">
      <a class="nav-link" href="/profile/index">
          <i class="fas fa-fw fa-user"></i>
          <span>Profile</span></a>
      </li>
      @endhasrole
      @hasrole('staff')
      <li class="nav-item active">
      <a class="nav-link" href="/edit">
          <i class="fas fa-fw fa-pen"></i>
          <span>Edit Profile</span></a>
      </li>
      @endhasrole
      @hasrole('admin')
      <li class="nav-item active">
      <a class="nav-link" href="/admin/users/profile">
          <i class="fas fa-fw fa-user"></i>
          <span>Profile</span></a>
      </li>
      @endhasrole
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="fas fa-fw fa-key"></i>
          <span>Logout</span></a>
            
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
            </form>
          
      </li>
      @endguest
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        @guest
        
        @else
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
          @hasrole('admin')<a href="#">Admin Dashboard</a>@endhasrole
          @hasrole('staff')<a href="#">Staff Dashboard</a>@endhasrole
          @hasrole('security')<a href="#">Security Dashboard</a>@endhasrole
          </li>
        </ol>
        @endguest

        <!-- DataTables Example -->
        <div class="card mb-3">
        @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
        @include('partials.alert')
            @yield('content')
            @yield('sms')

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © SMDF 2019</span>
          </div>
        </div>
      </footer>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>

  <!-- Sms Script -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script>
    $('#myForm').submit(function(){
        $('#submitBtn').html('Sending...');
    });
</script>

</body>

</html>
