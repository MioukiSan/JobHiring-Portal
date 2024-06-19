@if ($hiring_status == 'Closed')
<!-- Button trigger modal -->
<button type="button" class="btn btn-sm text-light float-end" style="background-color: #000789;"  data-bs-toggle="modal" data-bs-target="#shortlistingModal" title="Confirm and Reject Applicants">
    Shortlist Applicants
  </button>
  <div class="modal fade" id="shortlistingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Select Applicants</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
                If you are now selecting applicants to go on to the next stage, it confirms that you have already read the documents of each applicant.
            </div>
            <small>
                Instruction: Check the box if the applicant passed the Phase 1 shortlisting.
            </small>
            <div class="mb-3">
                <button type="button" class="btn btn-sm float-end" id="selectAll">Select All</button>
            </div>
            <p><b>Applicants</b></p>
            <form method="POST" action="{{route('selectApplicant', ['hiringID' => $hiring_id])}}">
              @csrf
              @foreach ($applicants as $select)
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="{{ $select['applicant_id'] }}" name="applicantSelected[]" id="applicantSelected{{ $select['applicant_id'] }}">
                      <label class="form-check-label" for="applicantSelected{{ $select['applicant_id'] }}">
                          {{ $select['user_name'] }}
                      </label>
                  </div>
              @endforeach
              <div class="text-center">
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @elseif ($hiring_status == 'Competency Exam')
  <button type="button" class="btn text-light btn-sm float-end" class="btn btn-sm text-light float-end" style="background-color: #000789;"  data-bs-toggle="modal" data-bs-target="#shortlistingModal" title="Confirm and Reject Applicants">
    Final Shortlist Applicants
  </button>
  <div class="modal fade" id="shortlistingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Select Applicants</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
               This is the final shortlisting of the applicants. Make sure that you have you already reviewed the competency exam and pre-employement result of the applicants.
            </div>
            <small>
                Instruction: Check the box if the applicant can continue to the initial interview.
            </small>
            <div class="mb-3">
                <button type="button" class="btn btn-sm float-end" id="selectAll">Select All</button>
            </div>
            <p><b>Applicants</b></p>
            <form method="POST" action="{{route('selectApplicant', ['hiringID' => $hiring_id])}}">
              @csrf
              @foreach ($applicants as $select)
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="{{ $select['applicant_id'] }}" name="applicantSelected[]" id="applicantSelected{{ $select['applicant_id'] }}">
                      <label class="form-check-label" for="applicantSelected{{ $select['applicant_id'] }}">
                          {{ $select['user_name'] }}
                      </label>
                  </div>
              @endforeach
              <div class="text-center">
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllBtn = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.form-check-input');

        selectAllBtn.addEventListener('click', function() {
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

            if (allChecked) {
                checkboxes.forEach(checkbox => checkbox.checked = false);
                selectAllBtn.textContent = 'Select All';
            } else {
                checkboxes.forEach(checkbox => checkbox.checked = true);
                selectAllBtn.textContent = 'Unselect All';
            }
        });
    }); 
</script>