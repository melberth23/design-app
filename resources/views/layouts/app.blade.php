<!DOCTYPE html>
<html lang="en">

{{-- Include Head --}}
@include('common.head')

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('common.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('common.header')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('common.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    @include('common.logout-modal')

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('js/app.js')}}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript">
        jQuery(function($) {
            $(function () {
                $('.datepicker').datepicker({ dateFormat: 'mm-dd-yy' });
                $('[data-toggle="tooltip"]').tooltip();
            });
        });
    </script>
    @hasrole('Designer')
    <script type="text/javascript">
        jQuery(function($) {
            $(function () {
                
                $('#reviewModal').on('shown.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var ref = button.data('ref');
                    var modal = $(this);
                    modal.find('.modal-body #request_ref_id').val(ref);
                })

            });
        });
    </script>
    @endhasrole

    <!-- Custom scripts for all pages-->
    <script src="{{asset('admin/js/sb-admin-2.js')}}"></script>

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

    @yield('scripts')
</body>

</html>