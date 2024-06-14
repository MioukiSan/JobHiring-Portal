<!-- Button to View Requirements -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewRequirementsModal{{ $applicant['user_id'] }}">
    Click to View
</button>

<!-- Modal for Viewing Requirements -->
<div class="modal fade" id="viewRequirementsModal{{ $applicant['user_id'] }}" tabindex="-1" aria-labelledby="viewRequirementsModalLabel{{ $applicant['user_id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRequirementsModalLabel{{ $applicant['user_id'] }}">View Requirements</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><b>Requirements:</b></p>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#viewCSCFormModal{{ $applicant['user_id'] }}">
                        CSC Form
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#viewTORModal{{ $applicant['user_id'] }}">
                        TOR/Diploma
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#viewTCModal{{ $applicant['user_id'] }}">
                        Training Certificate
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#viewEligibilityModal{{ $applicant['user_id'] }}">
                        Eligibility
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for CSC Form -->
<div class="modal fade" id="viewCSCFormModal{{ $applicant['user_id'] }}" tabindex="-1" aria-labelledby="viewCSCFormModalLabel{{ $applicant['user_id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCSCFormModalLabel{{ $applicant['user_id'] }}">CSC Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe src="{{ asset('storage/' . $applicant['csc_form']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal for TOR/Diploma -->
<div class="modal fade" id="viewTORModal{{ $applicant['user_id'] }}" tabindex="-1" aria-labelledby="viewTORModalLabel{{ $applicant['user_id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTORModalLabel{{ $applicant['user_id'] }}">TOR/Diploma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe src="{{ asset('storage/' . $applicant['tor_diploma']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Training Certificate -->
<div class="modal fade" id="viewTCModal{{ $applicant['user_id'] }}" tabindex="-1" aria-labelledby="viewTCModalLabel{{ $applicant['user_id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTCModalLabel{{ $applicant['user_id'] }}">Training Certificate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe src="{{ asset('storage/' . $applicant['training_cert']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Eligibility -->
<div class="modal fade" id="viewEligibilityModal{{ $applicant['user_id'] }}" tabindex="-1" aria-labelledby="viewEligibilityModalLabel{{ $applicant['user_id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEligibilityModalLabel{{ $applicant['user_id'] }}">Eligibility</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe src="{{ asset('storage/' . $applicant['eligibility']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
            </div>
        </div>
    </div>
</div>
