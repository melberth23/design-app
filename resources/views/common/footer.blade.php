<footer class="sticky-footer bg-white mt-4">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; {{ config('app.name', 'Laravel') }} @ {{date('Y')}}</span>
        </div>
    </div>
</footer>
<script type="text/javascript">
function openNav(elem) {
    document.getElementById(elem).style.width = "280px";
}

function closeNav(elem) {
    document.getElementById(elem).style.width = "0";
}
</script>