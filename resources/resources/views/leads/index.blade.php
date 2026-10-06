@extends('app')

@section('head')
    <title>Lead Management</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        #lead-calendar { min-height: 650px; }
        #lead-calendar .fc-event { cursor: pointer; border: 0; padding: 2px 4px; }
        #lead-calendar .lead-overdue { box-shadow: inset 0 0 0 2px #dd4b39; }
        .lead-status-key .badge { margin: 2px 4px 2px 0; padding: 6px 9px; }
        #detail-modal { color: #334155; }
        #detail-modal .lead-detail-dialog { max-width: 1120px; width: calc(100% - 32px); margin: 24px auto; }
        #detail-modal .modal-content { border: 0; border-radius: 14px; box-shadow: 0 24px 70px rgba(15, 23, 42, .3); overflow: hidden; }
        #detail-modal .lead-detail-header { background: linear-gradient(135deg, #0f4c81 0%, #1379b9 100%); border: 0; color: #fff; padding: 18px 24px; }
        #detail-modal .lead-title-wrap { align-items: center; display: flex; min-width: 0; }
        #detail-modal .lead-avatar { align-items: center; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.28); border-radius: 12px; display: flex; flex: 0 0 46px; font-size: 16px; font-weight: 700; height: 46px; justify-content: center; letter-spacing: .5px; margin-right: 13px; }
        #detail-modal .modal-title { color: #fff; font-size: 20px; font-weight: 600; line-height: 1.2; margin: 0 0 4px; }
        #detail-modal .lead-header-meta { color: rgba(255,255,255,.75); font-size: 12px; }
        #detail-modal .lead-header-meta span + span:before { content: '\2022'; margin: 0 8px; }
        #detail-modal .lead-header-actions { align-items: center; display: flex; margin-left: 16px; }
        #detail-modal .lead-header-actions .badge { border: 1px solid rgba(255,255,255,.25); font-size: 11px; padding: 7px 11px; }
        #detail-modal .lead-detail-header .close { color: #fff; margin: 0 0 0 14px; opacity: .85; padding: 6px; text-shadow: none; }
        #detail-modal .lead-detail-header .close:hover { opacity: 1; }
        #detail-modal .modal-body { background: #f4f7fb; max-height: calc(100vh - 100px); overflow-y: auto; padding: 20px; }
        #detail-modal .lead-panel { background: #fff; border: 1px solid #e6ebf1; border-radius: 10px; box-shadow: 0 3px 12px rgba(15,23,42,.04); margin-bottom: 16px; overflow: hidden; }
        #detail-modal .lead-panel-header { align-items: center; border-bottom: 1px solid #edf1f5; display: flex; justify-content: space-between; padding: 14px 16px; }
        #detail-modal .lead-panel-title { color: #1e293b; font-size: 14px; font-weight: 600; margin: 0; }
        #detail-modal .lead-panel-title i { color: #1683bd; margin-right: 7px; }
        #detail-modal .lead-panel-subtitle { color: #94a3b8; font-size: 11px; margin: 3px 0 0; }
        #detail-modal .lead-panel-body { padding: 16px; }
        #detail-modal .lead-summary { background: linear-gradient(145deg, #fff 0%, #f7fbff 100%); padding: 16px; }
        #detail-modal .lead-summary-name { color: #172033; font-size: 17px; font-weight: 600; margin: 0 0 13px; }
        #detail-modal .lead-contact-item { align-items: flex-start; display: flex; margin-top: 10px; min-width: 0; }
        #detail-modal .lead-contact-icon { align-items: center; background: #eaf5fb; border-radius: 8px; color: #1683bd; display: flex; flex: 0 0 32px; height: 32px; justify-content: center; margin-right: 10px; }
        #detail-modal .lead-contact-copy { line-height: 1.25; min-width: 0; }
        #detail-modal .lead-contact-label { color: #94a3b8; display: block; font-size: 10px; letter-spacing: .45px; margin-bottom: 2px; text-transform: uppercase; }
        #detail-modal .lead-contact-value { color: #475569; display: block; font-size: 12px; overflow-wrap: anywhere; }
        #detail-modal label { color: #64748b; font-size: 11px; font-weight: 600; margin-bottom: 5px; }
        #detail-modal .form-group { margin-bottom: 13px; }
        #detail-modal .form-control { border-color: #dce3eb; border-radius: 6px; color: #334155; font-size: 12px; min-height: 36px; }
        #detail-modal textarea.form-control { min-height: 70px; resize: vertical; }
        #detail-modal .form-control:focus { border-color: #39a7d8; box-shadow: 0 0 0 3px rgba(57,167,216,.12); }
        #detail-modal .lead-form-actions { align-items: center; display: flex; flex-wrap: wrap; gap: 8px; }
        #detail-modal .lead-form-actions .btn { border-radius: 6px; font-size: 12px; font-weight: 600; padding: 8px 14px; }
        #detail-modal .btn-save-activity { background: #08b98e; border-color: #08b98e; color: #fff; }
        #detail-modal .btn-save-activity:hover { background: #079d79; border-color: #079d79; color: #fff; }
        #detail-modal .lead-timeline { max-height: 610px; overflow-y: auto; padding: 18px 18px 4px 29px; }
        #detail-modal .lead-timeline-item { border-left: 2px solid #dce7ef; padding: 0 0 18px 20px; position: relative; }
        #detail-modal .lead-timeline-item:before { background: #fff; border: 3px solid #1683bd; border-radius: 50%; content: ''; height: 14px; left: -8px; position: absolute; top: 11px; width: 14px; }
        #detail-modal .lead-timeline-item:last-child { border-left-color: transparent; padding-bottom: 8px; }
        #detail-modal .timeline-card { background: #fff; border: 1px solid #e6ebf1; border-radius: 8px; padding: 12px 14px; transition: box-shadow .2s ease, transform .2s ease; }
        #detail-modal .timeline-card:hover { box-shadow: 0 5px 16px rgba(15,23,42,.08); transform: translateY(-1px); }
        #detail-modal .timeline-title { color: #26364a; font-size: 13px; font-weight: 600; }
        #detail-modal .timeline-meta { color: #94a3b8; font-size: 10px; margin-top: 5px; }
        #detail-modal .timeline-schedule { background: #f2f8fc; border-radius: 5px; color: #476276; font-size: 11px; margin-top: 9px; padding: 7px 9px; }
        #detail-modal .timeline-remarks { color: #64748b; font-size: 12px; line-height: 1.5; margin-top: 8px; }
        #detail-modal .timeline-empty { color: #94a3b8; padding: 70px 20px; text-align: center; }
        #detail-modal .timeline-empty i { color: #cbd5e1; display: block; font-size: 34px; margin-bottom: 10px; }
        #detail-modal .badge { border-radius: 4px; font-weight: 500; }
        .bg-purple { background-color: #605ca8 !important; color: #fff; }
        .bg-warning { color: #212529 !important; }
        .validation-errors ul { margin-bottom: 0; padding-left: 20px; }
        @media (max-width: 991.98px) {
            #detail-modal .lead-detail-dialog { margin: 12px auto; width: calc(100% - 20px); }
            #detail-modal .modal-body { max-height: calc(100vh - 76px); }
            #detail-modal .lead-timeline { max-height: none; }
        }
        @media (max-width: 575.98px) {
            #detail-modal .lead-detail-header { padding: 14px 16px; }
            #detail-modal .lead-avatar { display: none; }
            #detail-modal .lead-header-meta { display: none; }
            #detail-modal .lead-header-actions { margin-left: 8px; }
            #detail-modal .modal-body { padding: 12px; }
        }
    </style>
@stop

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Lead Management</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Leads</li>
            </ol>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="box-subtitle mb-1"><i class="fa fa-calendar"></i> FOLLOW-UP CALENDAR</h6>
                        <div class="lead-status-key">
                            @foreach ($statuses as $status => $meta)
                                <span class="badge {{ $meta['class'] }}">{{ $meta['label'] }}</span>
                            @endforeach
                            <span class="badge badge-light" style="border:2px solid #dd4b39;">Overdue</span>
                        </div>
                    </div>
                    @if ($canAdd)
                        <button class="btn btn-info" id="open-add-lead"><i class="fa fa-plus-circle"></i> Add Lead</button>
                    @endif
                </div>
                <div class="box-body">
                    <div class="row mb-3">
                        @if ($isAdmin)
                            <div class="col-md-2">
                                <label>View</label>
                                <select id="filter-scope" class="form-control">
                                    <option value="all">All Leads</option>
                                    <option value="mine">My Leads</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Assigned Staff</label>
                                <select id="filter-assignee" class="form-control select2">
                                    <option value="">All Staff</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label>Status</label>
                            <select id="filter-status" class="form-control select2">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status => $meta)
                                    <option value="{{ $status }}">{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="clear-filters" class="btn btn-secondary btn-block">Clear Filters</button>
                        </div>
                    </div>
                    <div id="lead-calendar"></div>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h6 class="box-subtitle"><i class="fa fa-list"></i> LEADS</h6>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered data-table" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Next Follow-up</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="lead-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="lead-form">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Lead</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger validation-errors d-none"></div>
                        <input type="hidden" name="reuse_party_id" id="reuse-party-id">
                        <input type="hidden" name="force_duplicate" id="force-duplicate" value="0">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" name="party_name" class="form-control" maxlength="100" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control pakistani-mobile" maxlength="11" inputmode="numeric" autocomplete="tel-national" placeholder="03XXXXXXXXX" pattern="03[0-9]{9}" title="Enter an 11-digit Pakistani mobile number, e.g. 03001234567" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email <small>(optional)</small></label>
                                <input type="email" name="party_email" class="form-control" maxlength="255">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Address <small>(optional)</small></label>
                                <input type="text" name="address" class="form-control" maxlength="200">
                            </div>
                            <div class="form-group col-md-4">
                                <label>First Follow-up Date <span class="text-danger">*</span></label>
                                <input type="date" name="follow_up_date" class="form-control" min="{{ now()->toDateString() }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Time <small>(optional)</small></label>
                                <input type="time" name="follow_up_time" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Assigned To <span class="text-danger">*</span></label>
                                <select name="assigned_to" class="form-control select2-modal" {{ $isAdmin ? '' : 'disabled' }}>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ auth()->id() == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @unless ($isAdmin)<input type="hidden" name="assigned_to" value="{{ auth()->id() }}">@endunless
                            </div>
                            <div class="form-group col-md-12">
                                <label>Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3" maxlength="2000"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save Lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detail-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog lead-detail-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header lead-detail-header">
                    <div class="lead-title-wrap">
                        <div class="lead-avatar" id="detail-avatar">LD</div>
                        <div>
                            <h5 class="modal-title" id="detail-header-name">Lead Details</h5>
                            <div class="lead-header-meta">
                                <span id="detail-header-assignee">Unassigned</span>
                                <span id="detail-header-followup">No follow-up scheduled</span>
                            </div>
                        </div>
                    </div>
                    <div class="lead-header-actions">
                        <span id="detail-status" class="badge"></span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger validation-errors d-none"></div>
                    <input type="hidden" id="detail-party-id">
                    <div class="row align-items-start">
                        <div class="col-lg-5 pr-lg-2">
                            <div class="lead-panel lead-summary">
                                <h5 id="detail-name" class="lead-summary-name"></h5>
                                <div class="row">
                                    <div class="col-sm-6 col-lg-12 lead-contact-item">
                                        <span class="lead-contact-icon"><i class="fa fa-phone"></i></span>
                                        <span class="lead-contact-copy"><small class="lead-contact-label">Phone</small><span id="detail-phone" class="lead-contact-value"></span></span>
                                    </div>
                                    <div class="col-sm-6 col-lg-12 lead-contact-item">
                                        <span class="lead-contact-icon"><i class="fa fa-envelope"></i></span>
                                        <span class="lead-contact-copy"><small class="lead-contact-label">Email</small><span id="detail-email" class="lead-contact-value"></span></span>
                                    </div>
                                    <div class="col-12 lead-contact-item">
                                        <span class="lead-contact-icon"><i class="fa fa-map-marker"></i></span>
                                        <span class="lead-contact-copy"><small class="lead-contact-label">Address</small><span id="detail-address" class="lead-contact-value"></span></span>
                                    </div>
                                </div>
                            </div>

                            @if ($canEdit)
                                <form id="contact-form" class="lead-panel">
                                    <div class="lead-panel-header">
                                        <div><h6 class="lead-panel-title"><i class="fa fa-address-card-o"></i> Contact Information</h6><p class="lead-panel-subtitle">Keep the lead's details up to date</p></div>
                                    </div>
                                    <div class="lead-panel-body">
                                        <div class="row">
                                            <div class="form-group col-sm-6"><label>Name <span class="text-danger">*</span></label><input name="party_name" class="form-control" required></div>
                                            <div class="form-group col-sm-6"><label>Phone <span class="text-danger">*</span></label><input type="tel" name="phone" class="form-control pakistani-mobile" maxlength="11" inputmode="numeric" autocomplete="tel-national" placeholder="03XXXXXXXXX" pattern="03[0-9]{9}" title="Enter an 11-digit Pakistani mobile number, e.g. 03001234567" required></div>
                                            <div class="form-group col-sm-6"><label>Email</label><input name="party_email" type="email" class="form-control"></div>
                                            <div class="form-group col-sm-6"><label>Address <small>(optional)</small></label><input name="address" class="form-control"></div>
                                        </div>
                                        <div class="lead-form-actions"><button class="btn btn-primary" type="submit"><i class="fa fa-check mr-1"></i> Update Contact</button></div>
                                    </div>
                                </form>

                                <form id="followup-form" class="lead-panel mb-0">
                                    <div class="lead-panel-header">
                                        <div><h6 class="lead-panel-title"><i class="fa fa-plus-circle"></i> Add Activity</h6><p class="lead-panel-subtitle">Log an update and schedule the next follow-up</p></div>
                                    </div>
                                    <div class="lead-panel-body"><div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Status</label>
                                            <select name="lead_status" class="form-control" required>
                                                @foreach ($statuses as $status => $meta)
                                                    @if ($status !== 'Converted')
                                                        <option value="{{ $status }}">{{ $meta['label'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Activity</label>
                                            <select name="activity_type" class="form-control" required>
                                                @foreach ($activityTypes as $type)
                                                    @if (!in_array($type, ['Lead Created', 'Conversion']))
                                                        <option value="{{ $type }}" {{ $type === 'Call' ? 'selected' : '' }}>{{ $type }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Next Date</label>
                                            <input type="date" name="follow_up_date" class="form-control" min="{{ now()->toDateString() }}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Next Time</label>
                                            <input type="time" name="follow_up_time" class="form-control">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Assigned To</label>
                                            <select name="assigned_to" class="form-control" {{ $isAdmin ? '' : 'disabled' }}>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            @unless ($isAdmin)<input type="hidden" name="assigned_to" value="{{ auth()->id() }}">@endunless
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Remarks</label>
                                            <textarea name="remarks" class="form-control" rows="2" maxlength="2000" placeholder="Add call notes, response or next action..."></textarea>
                                        </div>
                                    </div>
                                    <div class="lead-form-actions">
                                        <button class="btn btn-save-activity" type="submit"><i class="fa fa-save mr-1"></i> Save Activity</button>
                                        <button class="btn btn-purple" type="button" id="open-convert"><i class="fa fa-user-plus mr-1"></i> Convert to Customer</button>
                                    </div></div>
                                </form>
                            @endif
                        </div>
                        <div class="col-lg-7 pl-lg-2">
                            <div class="lead-panel mb-0">
                                <div class="lead-panel-header">
                                    <div><h6 class="lead-panel-title"><i class="fa fa-history"></i> Activity Timeline</h6><p class="lead-panel-subtitle">Complete communication and follow-up history</p></div>
                                    <span class="badge badge-light" id="timeline-count">0 activities</span>
                                </div>
                                <div id="lead-timeline" class="lead-timeline"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="convert-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="convert-form">
                    <div class="modal-header">
                        <h5 class="modal-title">Convert Lead to Customer</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger validation-errors d-none"></div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Customer Account Level <span class="text-danger">*</span></label>
                                <select name="account_group_id3" class="form-control select2-convert" required>
                                    <option value="">Select Level</option>
                                    @foreach ($accountGroups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Registration Type</label>
                                <select name="registration_type" class="form-control" required>
                                    <option value="Un Registered">Un Registered</option>
                                    <option value="Registered">Registered</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Login Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Show Products</label>
                                <select name="show_products" class="form-control" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" minlength="8" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Conversion Remarks</label>
                                <textarea name="remarks" class="form-control" rows="2" maxlength="2000"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-purple">Convert Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {
            const baseUrl = @json(URL::to('leads'));
            const csrf = $('meta[name="csrf-token"]').attr('content');
            const isAdmin = @json($isAdmin);
            const canEdit = @json($canEdit);
            const statusMeta = @json($statuses);
            let currentParty = null;

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
            $('.select2-modal').select2({ width: '100%', dropdownParent: $('#lead-modal') });
            $('.select2-convert').select2({ width: '100%', dropdownParent: $('#convert-modal') });

            $(document).on('input', '.pakistani-mobile', function () {
                let digits = $(this).val().replace(/\D/g, '');
                if (digits.indexOf('0092') === 0) digits = '0' + digits.substring(4);
                else if (digits.indexOf('92') === 0) digits = '0' + digits.substring(2);
                else if (digits.indexOf('3') === 0) digits = '0' + digits;
                $(this).val(digits.substring(0, 11));
            });

            function filters() {
                return {
                    scope: isAdmin ? $('#filter-scope').val() : 'mine',
                    assigned_to: isAdmin ? $('#filter-assignee').val() : '',
                    status: $('#filter-status').val()
                };
            }

            const table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: baseUrl + '/data', data: filters },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'party_name', name: 'party_name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'lead_status', orderable: false, searchable: false },
                    { data: 'assigned_to_name', orderable: false, searchable: false },
                    { data: 'next_follow_up', orderable: false, searchable: false },
                    { data: 'action', orderable: false, searchable: false }
                ]
            });

            $('#lead-calendar').fullCalendar({
                timezone: 'local',
                defaultView: 'month',
                header: { left: 'prev,next today', center: 'title', right: 'month,agendaWeek,agendaDay' },
                eventLimit: true,
                editable: false,
                events: {
                    url: baseUrl + '/events',
                    data: filters,
                    error: function () { Swal.fire('Error', 'Calendar events could not be loaded.', 'error'); }
                },
                dayClick: function (date) {
                    @if ($canAdd)
                        openLeadForm(date.format('YYYY-MM-DD'));
                    @endif
                },
                eventClick: function (event) { openLead(event.party_id); }
            });

            function refreshAll() {
                table.ajax.reload(null, false);
                $('#lead-calendar').fullCalendar('refetchEvents');
            }

            $('#filter-scope, #filter-assignee, #filter-status').on('change', refreshAll);
            $('#clear-filters').on('click', function () {
                if (isAdmin) { $('#filter-scope').val('all'); $('#filter-assignee').val('').trigger('change.select2'); }
                $('#filter-status').val('').trigger('change.select2');
                refreshAll();
            });

            $('#open-add-lead').on('click', function () { openLeadForm(moment().format('YYYY-MM-DD')); });
            function openLeadForm(date) {
                $('#lead-form')[0].reset();
                $('#reuse-party-id').val('');
                $('#force-duplicate').val('0');
                $('#lead-form [name="follow_up_date"]').val(date);
                $('#lead-form [name="assigned_to"]').val(@json(auth()->id())).trigger('change');
                hideErrors($('#lead-form'));
                $('#lead-modal').modal('show');
            }

            $('#lead-form').on('submit', function (e) {
                e.preventDefault();
                submitLead($(this).serialize());
            });

            function submitLead(payload) {
                $.post(baseUrl, payload).done(function (response) {
                    $('#lead-modal').modal('hide');
                    refreshAll();
                    Swal.fire('Saved', response.message, 'success');
                }).fail(function (xhr) {
                    if (xhr.status === 409 && xhr.responseJSON.duplicates) {
                        showDuplicateChoice(xhr.responseJSON.duplicates);
                    } else {
                        showErrors($('#lead-form'), xhr);
                    }
                });
            }

            function showDuplicateChoice(duplicates) {
                const options = duplicates.map(function (item) {
                    return '<option value="' + item.id + '">' + escapeHtml(item.party_name) + ' — ' + escapeHtml(item.phone || '') + ' (' + escapeHtml(item.role || 'Party') + ')</option>';
                }).join('');
                Swal.fire({
                    title: 'Matching party found',
                    html: '<p>Select the existing party to add this follow-up, or create a separate Lead.</p><select id="duplicate-choice" class="form-control">' + options + '</select>',
                    icon: 'warning',
                    showDenyButton: true,
                    confirmButtonText: 'Reuse selected',
                    denyButtonText: 'Create separate Lead',
                    preConfirm: function () { return $('#duplicate-choice').val(); }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $('#reuse-party-id').val(result.value);
                        submitLead($('#lead-form').serialize());
                    } else if (result.isDenied) {
                        $('#force-duplicate').val('1');
                        submitLead($('#lead-form').serialize());
                    }
                });
            }

            $(document).on('click', '.view-lead', function () { openLead($(this).data('id')); });
            function openLead(id) {
                $.get(baseUrl + '/' + id).done(function (response) {
                    currentParty = response.party;
                    $('#detail-party-id').val(currentParty.id);
                    $('#detail-name').text(currentParty.party_name);
                    $('#detail-phone').text(currentParty.phone || '—');
                    $('#detail-email').text(currentParty.party_email || '—');
                    $('#detail-address').text(currentParty.address || '—');
                    const meta = statusMeta[response.current_status] || { class: 'bg-secondary', label: response.current_status || 'New' };
                    $('#detail-status').attr('class', 'badge ' + meta.class).text(meta.label);
                    const initials = (currentParty.party_name || 'Lead').split(/\s+/).filter(Boolean).slice(0, 2).map(function (word) { return word.charAt(0); }).join('').toUpperCase();
                    $('#detail-avatar').text(initials || 'LD');
                    $('#detail-header-name').text(currentParty.party_name || 'Lead Details');
                    const openFollowUp = response.open_follow_up;
                    $('#detail-header-assignee').text((openFollowUp && openFollowUp.assigned_to_name) || 'Unassigned');
                    $('#detail-header-followup').text(openFollowUp && openFollowUp.follow_up_date
                        ? 'Next: ' + openFollowUp.follow_up_date + (openFollowUp.follow_up_time ? ' at ' + openFollowUp.follow_up_time : '')
                        : 'No follow-up scheduled');
                    $('#contact-form [name="party_name"]').val(currentParty.party_name);
                    $('#contact-form [name="phone"]').val(currentParty.phone);
                    $('#contact-form [name="party_email"]').val(currentParty.party_email);
                    $('#contact-form [name="address"]').val(currentParty.address);
                    $('#followup-form [name="lead_status"]').val(
                        ['Converted', 'Lost'].indexOf(response.current_status) >= 0 ? 'New' : (response.current_status || 'New')
                    );
                    $('#followup-form [name="assigned_to"]').val(response.assigned_to || @json(auth()->id()));
                    $('#followup-form [name="follow_up_date"], #followup-form [name="follow_up_time"], #followup-form [name="remarks"]').val('');
                    renderTimeline(response.history);
                    hideErrors($('#detail-modal'));
                    $('#open-convert').toggle(currentParty.role !== 'Customer' && response.current_status !== 'Converted');
                    $('#detail-modal').modal('show');
                }).fail(function (xhr) { ajaxAlert(xhr); });
            }

            function renderTimeline(history) {
                $('#timeline-count').text(history.length + (history.length === 1 ? ' activity' : ' activities'));
                if (!history.length) { $('#lead-timeline').html('<div class="timeline-empty"><i class="fa fa-calendar-check-o"></i>No activity has been recorded yet.</div>'); return; }
                const html = history.map(function (item) {
                    const schedule = item.follow_up_date
                        ? '<div class="timeline-schedule"><i class="fa fa-calendar mr-1"></i> <strong>Follow-up:</strong> ' + escapeHtml(item.follow_up_date) + (item.follow_up_time ? ' at ' + escapeHtml(item.follow_up_time) : ' (all day)') + (item.is_overdue ? ' <span class="badge badge-danger ml-1">Overdue</span>' : '') + '</div>'
                        : '';
                    let state = '';
                    if (item.completed_at) state = '<span class="badge badge-success">Completed</span>';
                    if (item.cancelled_at) state = '<span class="badge badge-secondary">Rescheduled</span>';
                    return '<div class="lead-timeline-item">' +
                        '<div class="timeline-card"><div class="d-flex justify-content-between align-items-start"><div><span class="badge ' + item.status_class + ' mr-1">' + escapeHtml(item.lead_status) + '</span> <span class="timeline-title">' + escapeHtml(item.activity_type) + '</span></div>' + state + '</div>' +
                        '<div class="timeline-meta"><i class="fa fa-clock-o mr-1"></i>' + escapeHtml(item.created_at || '') + ' &nbsp;&bull;&nbsp; <i class="fa fa-user-o mr-1"></i>' + escapeHtml(item.assigned_to_name || 'Unassigned') + '</div>' +
                        schedule + (item.remarks ? '<div class="timeline-remarks">' + escapeHtml(item.remarks) + '</div>' : '') + '</div></div>';
                }).join('');
                $('#lead-timeline').html(html);
            }

            $('#contact-form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({ url: baseUrl + '/' + currentParty.id, method: 'PATCH', data: $(this).serialize() })
                    .done(function (response) { refreshAll(); openLead(currentParty.id); Swal.fire('Updated', response.message, 'success'); })
                    .fail(function (xhr) { showErrors($('#detail-modal'), xhr); });
            });

            $('#followup-form [name="lead_status"]').on('change', function () {
                const lost = $(this).val() === 'Lost';
                $('#followup-form [name="follow_up_date"], #followup-form [name="follow_up_time"]').prop('disabled', lost).val(lost ? '' : function () { return $(this).val(); });
            });

            $('#followup-form').on('submit', function (e) {
                e.preventDefault();
                $.post(baseUrl + '/' + currentParty.id + '/follow-ups', $(this).serialize())
                    .done(function (response) { refreshAll(); openLead(currentParty.id); Swal.fire('Saved', response.message, 'success'); })
                    .fail(function (xhr) { showErrors($('#detail-modal'), xhr); });
            });

            $('#open-convert').on('click', function () {
                $('#convert-form')[0].reset();
                $('#convert-form [name="email"]').val(currentParty.party_email || '');
                hideErrors($('#convert-form'));
                $('#convert-modal').modal('show');
            });

            $('#convert-form').on('submit', function (e) {
                e.preventDefault();
                $.post(baseUrl + '/' + currentParty.id + '/convert', $(this).serialize())
                    .done(function (response) {
                        $('#convert-modal, #detail-modal').modal('hide');
                        refreshAll();
                        Swal.fire('Converted', response.message, 'success');
                    }).fail(function (xhr) { showErrors($('#convert-form'), xhr); });
            });

            function hideErrors(container) { container.find('.validation-errors').addClass('d-none').empty(); }
            function showErrors(container, xhr) {
                const response = xhr.responseJSON || {};
                let errors = [];
                if (response.errors) Object.keys(response.errors).forEach(function (key) { errors = errors.concat(response.errors[key]); });
                if (!errors.length) errors.push(response.message || 'Something went wrong.');
                container.find('.validation-errors').removeClass('d-none').html('<ul><li>' + errors.map(escapeHtml).join('</li><li>') + '</li></ul>');
            }
            function ajaxAlert(xhr) { Swal.fire('Error', (xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong.', 'error'); }
            function escapeHtml(value) { return $('<div>').text(value == null ? '' : String(value)).html(); }
        });
    </script>
@stop
