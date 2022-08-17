<form id="request-delete-form" method="POST" action="{{ route('request.leavereview') }}">
    @csrf
    <div class="modal fade" id="leaveReviewModal" tabindex="-1" role="dialog" aria-labelledby="leaveReviewModalExample"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="leaveReviewModalExample">Give us feedback about your experience</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-dark">
                    <div class="rate-star">
                        <label>How would you rate working with the designer?</label>
                        <fieldset class="rating star">
                            <input type="radio" id="rate_designer5" name="rate_designer" value="5" /><label class = "full" for="rate_designer5"></label>
                            <input type="radio" id="rate_designer4" name="rate_designer" value="4" /><label class = "full" for="rate_designer4"></label>
                            <input type="radio" id="rate_designer3" name="rate_designer" value="3" /><label class = "full" for="rate_designer3"></label>
                            <input type="radio" id="rate_designer2" name="rate_designer" value="2" /><label class = "full" for="rate_designer2"></label>
                            <input type="radio" id="rate_designer1" name="rate_designer" value="1" /><label class = "full" for="rate_designer1"></label>
                        </fieldset>
                    </div>
                    <div class="rate-star">
                        <label class="pt-3">How was your communication with the designer?</label>
                        <fieldset class="rating star">
                            <input type="radio" id="com_designer5" name="com_designer" value="5" /><label class = "full" for="com_designer5"></label>
                            <input type="radio" id="com_designer4" name="com_designer" value="4" /><label class = "full" for="com_designer4"></label>
                            <input type="radio" id="com_designer3" name="com_designer" value="3" /><label class = "full" for="com_designer3"></label>
                            <input type="radio" id="com_designer2" name="com_designer" value="2" /><label class = "full" for="com_designer2"></label>
                            <input type="radio" id="com_designer1" name="com_designer" value="1" /><label class = "full" for="com_designer1"></label>
                        </fieldset>
                    </div>
                    <div class="experience_to_designer">
                        <label class="pt-3">Share your experience in details working with the designer</label>
                        <textarea id="experience_to_designer" class="form-control form-control-user" name="experience_to_designer"></textarea>
                    </div>
                    <div class="work_again">
                        <label class="pt-3">Would you like to work again with the designer?</label>
                    </div>
                    <div class="work_again-buttons btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-primary">
                            <input type="radio" name="work_again_option" value="yes"> <img src="{{ asset('images/like.svg') }}" > Yes
                        </label>
                        <label class="btn btn-danger">
                            <input type="radio" name="work_again_option" value="no"> <img src="{{ asset('images/dislike.svg') }}" > No
                        </label>
                    </div>
                    <div class="rate-star">
                        <label class="pt-3">How would you rate using our platform?</label>
                        <fieldset class="rating star">
                            <input type="radio" id="rate_platform5" name="rate_platform" value="5" /><label class = "full" for="rate_platform5"></label>
                            <input type="radio" id="rate_platform4" name="rate_platform" value="4" /><label class = "full" for="rate_platform4"></label>
                            <input type="radio" id="rate_platform3" name="rate_platform" value="3" /><label class = "full" for="rate_platform3"></label>
                            <input type="radio" id="rate_platform2" name="rate_platform" value="2" /><label class = "full" for="rate_platform2"></label>
                            <input type="radio" id="rate_platform1" name="rate_platform" value="1" /><label class = "full" for="rate_platform1"></label>
                        </fieldset>
                    </div>
                    <div class="experience_platform">
                        <label class="pt-3">Tell us your experience using our platform?</label>
                        <textarea id="experience_platform" class="form-control form-control-user" name="experience_platform"></textarea>
                    </div>
                    <div class="suggestion">
                        <label class="pt-3">Do you have suggestion, how we can improve our platform?</label>
                        <textarea id="suggestion" class="form-control form-control-user" name="suggestion"></textarea>
                    </div>
                    <div class="recommend">
                        <label class="pt-3">Would you recommend DesignsOwl to your friends?</label>
                    </div>
                    <div class="recommend-buttons btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-primary">
                            <input type="radio" name="recommend_option" value="yes"> <img src="{{ asset('images/like.svg') }}" > Yes
                        </label>
                        <label class="btn btn-danger">
                            <input type="radio" name="recommend_option" value="no"> <img src="{{ asset('images/dislike.svg') }}" > No
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Submit Review</button>
                        
                    <input type="hidden" id="request_id" name="id" value="">
                </div>
            </div>
        </div>
    </div>
</form>