<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Admin | {{ $setting->nama_perusahaan }}</title>

    <link href="{{ asset($setting->path_logo) }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('pemda/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('pemda/css/style.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
   
        <div class="container">
                    
               <img src="{{ asset('img/abid2.png') }}" alt=""  style="width: 100%; border-bottom-right-radius: 15px ">
            </div>

       
    <div class="container-fluid  ">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 py-5 py-lg-0" style="background: ">
                    <h6 class="text-primary text-uppercase font-weight-bold">Pemerintah Daerah Kabupaten Morowali</h6>
                    <h1 class="mb-2">{{ $setting->nama_perusahaan }}</h1>
                    <p class="mb-4">Sistem Informasi Monitoring Elpiji (3kg)</p>
                    <div class="row">
                        @php
                            $agent = DB::table('users')->where('level',1)->count();
                            $pangkalan = DB::table('users')->where('level',2)->count();
                            $pelanggan = DB::table('pelanggans')->count();
                        @endphp
                        <div class="col-sm-4">
                            <h1 class="text-primary mb-2" data-toggle="counter-up">{{ $agent }}</h1>
                            <h6 class="font-weight-bold mb-4">Agen</h6>
                        </div>
                        <div class="col-sm-4">
                            <h1 class="text-primary mb-2" data-toggle="counter-up">{{ $pangkalan }}</h1>
                            <h6 class="font-weight-bold mb-4">Pangkalan</h6>
                        </div>
                        <div class="col-sm-4">
                            <h1 class="text-primary mb-2" data-toggle="counter-up">{{ $pelanggan }}</h1>
                            <h6 class="font-weight-bold mb-4">Pengguna</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="bg-primary py-3 px-4 px-sm-5" style="border-radius: 15px;">
                        <center>
                            <img src="{{ asset('img/morowali.png') }}" width="150px">
                        </center>
                        <x-jet-validation-errors class="mb-4" style="color: white;" />
                        <form action="{{ isset($guard) ? url($guard.'/login') : route('login') }}" method="POST" class="py-3">
                            @csrf
                            <div class="form-group">
                                <input type="email" name="email" style="border-radius: 20px;" id="email"  class="form-control border-0 p-4" placeholder="Email" :value="old('email')" required="required" />
                               
                            </div>
                            <div class="form-group">
                                
                                <div class="input-group"  id="show_hide_password">
                                    
                                    <input type="password" style="border-radius: 20px;" id="password" name="password" class="form-control border-0 p-4" placeholder="Password" required="required" />
                                    {{-- <a href="" class="justify-content-center" style="color: white; float: right;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a> --}}
                                    
                                </div>

                                
                               
                            </div>
                          
                            <div >
                                <button class="btn btn-dark btn-block border-0 py-3" style="border-radius: 20px;" type="submit">Masuk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


     <!-- JavaScript Libraries -->
     <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
     <script src="{{ asset('pemda/lib/easing/easing.min.js') }}"></script>
     <script src="{{ asset('pemda/lib/waypoints/waypoints.min.js') }}"></script>
     <script src="{{ asset('pemda/lib/counterup/counterup.min.js') }}"></script>
     <script src="{{ asset('pemda/lib/owlcarousel/owl.carousel.min.js') }}"></script>
 
     <!-- Contact Javascript File -->
     <script src="{{ asset('pemda/mail/jqBootstrapValidation.min.js') }}"></script>
     <script src="{{ asset('pemda/mail/contact.js') }}"></script>
 
     <!-- Template Javascript -->
     <script src="{{ asset('pemda/js/main.js') }}"></script>

     <script>
         $(document).ready(function() {
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
    });
});
     </script>
</body>
</html>