<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalExample"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalExample">Please upload all your works before moving for review.</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('designer.addfilereview') }}" enctype="multipart/form-data" id="form-review-request">
                    @csrf

                    <input type="hidden" id="request_ref_id" name="id" value="">
                    <div class="text-dark py-3">
                        <label for="media">Add Images</label>
                        <input type="file" name="media[]" class="form-control-file" multiple >
                    </div>

                    <hr>

                    <div class="text-dark py-3">
                        <label for="documents">Add Documents</label>
                        <input type="file" name="documents[]" class="form-control-file" multiple >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-success" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('form-review-request').submit();">
                    Review
                </a>
            </div>
        </div>
    </div>
</div>
