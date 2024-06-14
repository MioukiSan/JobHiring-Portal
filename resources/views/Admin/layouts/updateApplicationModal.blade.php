<x-adminlte-modal id="updateApplication{{ $applicant['user_id'] }}" title="Update Application" v-centered>
    <div class="row">
      <div class="col-12">
        <div class="card card-tabs">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="data-pane-tab" data-toggle="pill" href="#data-pane" role="tab" aria-controls="data-pane" aria-selected="true">Application Results</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="update-pane-tab" data-toggle="pill" href="#update-pane" role="tab" aria-controls="update-pane" aria-selected="false" title="Upload exam files and results.">Update Application</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="data-pane" role="tabpanel" aria-labelledby="data-pane-tab">
                    @php
                        if($contract_type == 'COS'){
                            $exams = [
                                'competency' => ['label' => 'COMPETENCY EXAM', 'file' => 'competency', 'result' => 'competency_result'],
                                'pre_employment' => ['label' => 'PRE-EMPLOYMENT EXAM', 'file' => 'pre_employment', 'result' => 'pre_result'],
                                'initial' => ['label' => 'INITIAL INTERVIEW', 'file' => 'initial', 'result' => 'initial_result'],
                                'final' => ['label' => 'FINAL INTERVIEW', 'file' => null, 'result' => 'final']
                            ];
                        } elseif($contract_type == 'Permanent') {
                            if($applicant['job_type'] == 'SRS-1' || $applicant['job_type'] == 'Entry'){
                                $exams = [
                                    'pre_employment' => ['label' => 'PRE-EMPLOYMENT EXAM', 'file' => 'pre_employment', 'result' => 'pre_result']
                                ];
                            } else {
                                $exams = [];
                            }
                            $exams += [
                                'bei_status' => ['label' => 'Behavioral Event Interview', 'file' => 'bei_file', 'result' => 'bei_status'],
                                'psycho' => ['label' => 'Psychometric Test', 'file' => 'psycho', 'result' => 'psycho_result'],
                                'final' => ['label' => 'FINAL INTERVIEW', 'file' => null, 'result' => 'final']
                            ];
                        }
                    @endphp

                    <div class="row">
                        @foreach ($exams as $key => $exam)
                            <div class="col-12">
                                <p><b><i>{{ $exam['label'] }}</i></b></p>
                            </div>
                            @if (!empty($applicant[$exam['file']]) || !empty($applicant[$exam['result']]))
                                <div class="col-6">
                                    @if($exam['file'])
                                        <p><b>Exam file:</b></p>
                                    @endif
                                    <p><b> Result:</b></p>
                                </div>
                                <div class="col-6">
                                    @if($exam['file'])
                                        <p><a href="{{ asset('storage/' . $applicant[$exam['file']]) }}" target="_blank">Exam File</a></p>
                                    @endif
                                    <p class="{{ $applicant[$exam['result']] === 'Failed' ? 'text-danger' : ($applicant[$exam['result']] === 'Passed' ? 'text-success' : '') }}">
                                        {{ $applicant[$exam['result']] }}
                                    </p>
                                </div>
                            @else
                                <div class="col-12">
                                    <p>Not yet Updated</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="update-pane" role="tabpanel" aria-labelledby="update-pane-tab">
                    <div class="row">
                        <div class="col-5 col-sm-5">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                @if ($contract_type === 'COS')
                                    <a class="nav-link active" id="vert-tabs-competency-exam-tab" data-toggle="pill" href="#vert-tabs-competency-exam" role="tab" aria-controls="vert-tabs-competency-exam" aria-selected="true">Competency Exam</a>
                                    <a class="nav-link" id="vert-tabs-pre-employment-exam-tab" data-toggle="pill" href="#vert-tabs-pre-employment-exam" role="tab" aria-controls="vert-tabs-pre-employment-exam" aria-selected="false">Pre-employment Exam</a>
                                    <a class="nav-link" id="vert-tabs-initial-interview-tab" data-toggle="pill" href="#vert-tabs-initial-interview" role="tab" aria-controls="vert-tabs-initial-interview" aria-selected="false">Initial Interview</a>
                                    <a class="nav-link" id="vert-tabs-final-interview-tab" data-toggle="pill" href="#vert-tabs-final-interview" role="tab" aria-controls="vert-tabs-final-interview" aria-selected="false">Final Interview</a>
                                @elseif ($contract_type === 'Permanent')
                                    @if($applicant['job_type'] == 'SRS-1' || $applicant['job_type'] == 'Entry')
                                        <a class="nav-link  active" id="vert-tabs-pre-employment-exam-tab" data-toggle="pill" href="#vert-tabs-pre-employment-exam" role="tab" aria-controls="vert-tabs-pre-employment-exam" aria-selected="false">Pre-employment Exam</a>
                                    @endif
                                    <a class="nav-link" id="vert-tabs-bei-exam-tab" data-toggle="pill" href="#vert-tabs-bei-exam" role="tab" aria-controls="vert-tabs-bei-exam" aria-selected="true">BEI Exam</a>
                                    <a class="nav-link" id="vert-tabs-psycho-interview-tab" data-toggle="pill" href="#vert-tabs-psycho-interview" role="tab" aria-controls="vert-tabs-psycho-interview" aria-selected="false">Psychometric Test</a>
                                    <a class="nav-link" id="vert-tabs-final-interview-tab" data-toggle="pill" href="#vert-tabs-final-interview" role="tab" aria-controls="vert-tabs-final-interview" aria-selected="false">Final Interview</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-7 col-sm-7">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                <div class="tab-pane text-left fade show active" id="vert-tabs-competency-exam" role="tabpanel" aria-labelledby="vert-tabs-competency-exam-tab">
                                    @if (!empty($applicant['competency']) || !empty($applicant['competency_result']))
                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>Competency Exam file:</b></p>
                                                <p><b>Competency Exam Result:</b></p>
                                            </div>
                                            <div class="col-6">
                                                <p><a href="{{ asset('storage/' . $applicant['competency']) }}" target="_blank">Exam File</a></p>
                                                <p class="{{ $applicant['competency_result'] === 'Failed' ? 'text-danger' : ($applicant['competency_result'] === 'Passed' ? 'text-success' : '') }}">
                                                    {{ $applicant['competency_result'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{route('application.updateApplicant', ['for' => 'Competency', 'applicant_id' => $applicant['applicant_id']])}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="CompetencyFile" class="form-label">Competency Exam File</label>
                                                <input type="file" class="form-control-file border p-1" name="competencyFile">
                                            </div>
                                            <div class="form-group">
                                                <label for="CompetencyResult" class="form-label">Exam Result</label>
                                                <select name="CompetencyResult" class="form-control">
                                                    <option value="" disabled selected>Choose Exam Result</option>
                                                    <option value="Passed">Passed</option>
                                                    <option value="Failed">Failed</option>
                                                </select>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-pre-employment-exam" role="tabpanel" aria-labelledby="vert-tabs-pre-employment-exam-tab">
                                    @if (!empty($applicant['pre_employment']) || !empty($applicant['pre_result']))
                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>Exam file:</b></p>
                                                <p><b>Exam Result:</b></p>
                                            </div>
                                            <div class="col-6">
                                                <p><a href="{{ asset('storage/' . $applicant['pre_employment']) }}" target="_blank">Exam File</a></p>
                                                <p class="{{ $applicant['pre_result'] === 'Failed' ? 'text-danger' : ($applicant['pre_result'] === 'Passed' ? 'text-success' : '') }}">
                                                    {{ $applicant['pre_result'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                    <form action="{{route('application.updateApplicant', ['for' => 'Pre-Employment', 'applicant_id' => $applicant['applicant_id']])}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="preEmploymentFile" class="form-label">Pre-employment Exam File</label>
                                                <input type="file" class="form-control-file border p-1" name="preEmploymentFile">
                                            </div>
                                            <div class="form-group">
                                                <label for="preEmploymentResult" class="form-label">Exam Result</label>
                                                <select name="preEmploymentResult" class="form-control">
                                                    <option value="" disabled selected>Choose Exam Result</option>
                                                    <option value="Passed">Passed</option>
                                                    <option value="Failed">Failed</option>
                                                </select>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-initial-interview" role="tabpanel" aria-labelledby="vert-tabs-initial-interview-tab">
                                    @if (!empty($applicant['initial']) || !empty($applicant['initial_result']))
                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>file:</b></p>
                                                <p><b>Result:</b></p>
                                            </div>
                                            <div class="col-6">
                                                <p><a href="{{ asset('storage/' . $applicant['initial']) }}" target="_blank">Exam File</a></p>
                                                <p class="{{ $applicant['initial_result'] === 'Failed' ? 'text-danger' : ($applicant['initial_result'] === 'Passed' ? 'text-success' : '') }}">
                                                    {{ $applicant['initial_result'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                    <form action="{{route('application.updateApplicant', ['for' => 'Intial Interview', 'applicant_id' => $applicant['applicant_id']])}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="InitialFile" class="form-label">Initial Interview File</label>
                                                <input type="file" class="form-control-file border p-1" name="InitialFile">
                                            </div>
                                            <div class="form-group">
                                                <label for="InitialResult" class="form-label">Exam Result</label>
                                                <select name="InitialResult" class="form-control">
                                                    <option value="" disabled selected>Choose Exam Result</option>
                                                    <option value="Passed">Passed</option>
                                                    <option value="Failed">Failed</option>
                                                </select>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-final-interview" role="tabpanel" aria-labelledby="vert-tabs-final-interview-tab">
                                    @if (!empty($applicant['final']))
                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>Result:</b></p>
                                            </div>
                                            <div class="col-6">
                                                <p class="{{ $applicant['final'] === 'Failed' ? 'text-danger' : ($applicant['final'] === 'Passed' ? 'text-success' : '') }}">
                                                    {{ $applicant['final'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{route('application.updateApplicant', ['for' => 'Final Interview'])}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="FinalResult" class="form-label">Exam Result</label>
                                                <select name="FinalResult" class="form-control">
                                                    <option value="" disabled selected>Choose Exam Result</option>
                                                    <option value="Passed">Passed</option>
                                                    <option value="Failed">Failed</option>
                                                </select>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-bei-exam" role="tabpanel" aria-labelledby="vert-tabs-bei-exam-tab">
                                    @if (!empty($applicant['bei_status']))
                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>Result:</b></p>
                                            </div>
                                            <div class="col-6">
                                                <p class="{{ $applicant['bei_status'] === 'Failed' ? 'text-danger' : ($applicant['bei_status'] === 'Passed' ? 'text-success' : '') }}">
                                                    {{ $applicant['bei_status'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{route('application.updateApplicant', ['for' => 'BEI'])}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="BEIResult" class="form-label">Exam Result</label>
                                                <select name="BEIResult" class="form-control">
                                                    <option value="" disabled selected>Choose Exam Result</option>
                                                    <option value="Passed">Passed</option>
                                                    <option value="Failed">Failed</option>
                                                </select>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-psycho-interview" role="tabpanel" aria-labelledby="vert-tabs-psycho-interview-tab">
                                    @if (!empty($applicant['psycho_file']) || !empty($applicant['psycho_status']))
                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>Psychometric Test file:</b></p>
                                                <p><b>Psychometric Test Result:</b></p>
                                            </div>
                                            <div class="col-6">
                                                <p><a href="{{ asset('storage/' . $applicant['psycho_file']) }}" target="_blank">Test File</a></p>
                                                <p class="{{ $applicant['psycho_status'] === 'Failed' ? 'text-danger' : ($applicant['psycho_status'] === 'Passed' ? 'text-success' : '') }}">
                                                    {{ $applicant['psycho_status'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{route('application.updateApplicant', ['for' => 'Pre-Employment'])}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="PsychoFile" class="form-label">Psychometric Test File</label>
                                                <input type="file" class="form-control-file border p-1" name="PsychoFile">
                                            </div>
                                            <div class="form-group">
                                                <label for="PsychoResult" class="form-label">Test Result</label>
                                                <select name="PsychoResult" class="form-control">
                                                    <option value="" disabled selected>Choose Test Result</option>
                                                    <option value="Passed">Passed</option>
                                                    <option value="Failed">Failed</option>
                                                </select>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </x-adminlte-modal>       