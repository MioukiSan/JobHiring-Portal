<x-adminlte-button label="Click to View" data-toggle="modal" data-target="#viewRequirementsModal{{ $applicant['user_id'] }}" />
<x-adminlte-modal id="viewRequirementsModal{{ $applicant['user_id'] }}" title="View Requirements" v-centered>
    <div class="row">
        <div class="col-12">
            <p><b>Requirements:</b></p>
            <div class="btn-group">
                <x-adminlte-button label="CSC Form" data-toggle="modal" data-target="#viewCSCFormModal{{ $applicant['user_id'] }}" />
                <x-adminlte-button label="TOR/Diploma" data-toggle="modal" data-target="#viewTORModal{{ $applicant['user_id'] }}" />
                <x-adminlte-button label="Training Certificate" data-toggle="modal" data-target="#viewTCModal{{ $applicant['user_id'] }}" />
                <x-adminlte-button label="Eligibility" data-toggle="modal" data-target="#viewEligibilityModal{{ $applicant['user_id'] }}" />
            </div>
        </div>
    </div>
</x-adminlte-modal>
<x-adminlte-modal id="viewCSCFormModal{{ $applicant['user_id'] }}" title="CSC Form" v-centered size="lg">
    <iframe src="{{ asset('storage/' . $applicant['csc_form']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
</x-adminlte-modal>

<x-adminlte-modal id="viewTORModal{{ $applicant['user_id'] }}" title="TOR/Diploma" v-centered size="lg">
    <iframe src="{{ asset('storage/' . $applicant['tor_diploma']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
</x-adminlte-modal>

<x-adminlte-modal id="viewTCModal{{ $applicant['user_id'] }}" title="Training Certificate" v-centered size="lg">
    <iframe src="{{ asset('storage/' . $applicant['training_cert']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
</x-adminlte-modal>

<x-adminlte-modal id="viewEligibilityModal{{ $applicant['user_id'] }}" title="Eligibility" v-centered size="lg">
    <iframe src="{{ asset('storage/' . $applicant['eligibility']) }}" frameborder="0" style="width:100%;height:500px;"></iframe>
</x-adminlte-modal>                    