<!DOCTYPE html>
<html lang="en">

{{-- Include Head --}}
@include('common.fronthead')

<body id="page-top">

    <!-- Header -->
    @yield('header')

    <!-- Begin Page Content -->
    @yield('content')
    <!-- /.container-fluid -->

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('js/frontscripts.js')}}"></script>

    @yield('scripts')
</body>

</html>