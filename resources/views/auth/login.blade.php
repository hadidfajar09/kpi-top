<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">


    <style>
        .gradient-custom-2 {
/* fallback for old browsers */
background: #fccb90;

/* Chrome 10-25, Safari 5.1-6 */
background: -webkit-linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);

/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
}

@media (min-width: 768px) {
.gradient-form {
height: 100vh !important;
}
}
@media (min-width: 769px) {
.gradient-custom-2 {
border-top-right-radius: .3rem;
border-bottom-right-radius: .3rem;
}
}
    </style>
</head>
<body>
    <section class="h-100 gradient-form" style="background-color: #eee;">
        <div class="container h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">
              <div class="card rounded-3 text-black">
                <div class="row g-0">
                  <div class="col-lg-6">
                    <div class="card-body p-md-5 mx-md-4">
      
                      <div class="text-center">
                        <img src="{{ asset('img/logo-2022-05-29003335.png') }}"
                          style="width: 155px;" alt="logo">
                        <h4 class="mt-1 mb-5 pb-1">PT. TOP INDO MAJU</h4>
                      </div>
      
                      <form action="{{ isset($guard) ? url($guard.'/login') : route('login') }}" method="post">
                        @csrf
                        <p>Please login to your account</p>
                        <x-jet-validation-errors class="mb-4" style="color: white;" />


                        <div class="form-outline mb-4">
                            <label class="form-label" for="email">Email</label>
                          <input type="email" id="email" name="email" class="form-control"
                             :value="old('email')" required />
                        </div>
      
                        <div class="form-outline mb-4">
                            <label class="form-label" for="password">Password</label>
                          <input type="password" name="password" id="password"  class="form-control" />
                        </div>
      
                        <div class="text-center pt-1 mb-5 pb-1">
                          <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Log
                            in</button>
                        </div>
      
                      </form>
      
                    </div>
                  </div>
                  <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                    <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                      <h4 class="mb-4">Kontrol dan evaluasi hal yang penting dalam manajemen</h4>
                      <p class="small mb-0">KPI (Key Performance Indicator) adalah alat ukur yang menggambarkan efektivitas perusahaan dalam mencapai tujuan bisnisnya.

                        Perusahaan menggunakan KPI untuk mengukur kesuksesan pencapaian target mereka.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</body>
</html>