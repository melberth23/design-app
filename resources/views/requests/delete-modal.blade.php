<div class="modal fade" id="deleteRequestModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalExample"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalExample">Delete request</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Are you sure you want to delete your request?</div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('request-delete-form').submit();">
                    Delete
                </a>
                <form id="request-delete-form" method="POST" action="{{ route('request.destroy') }}">
                    @csrf
                    
                    <input type="hidden" id="request_id" name="id" value="">
                </form>
            </div>
        </div>
    </div>
</div>