@php
    use App\Models\SystemLogo;
    $system = SystemLogo::first();
@endphp
<div class="row" style="border-bottom: 2px solid;">
    <div class="col-12 col-sm-auto mb-3">
        <div class="mx-auto" style="width: 140px;">
            <img src="{{ asset('/root/upload/logo') }}/{{ $system->image }}" style="height: 140px; width: 140px;">
        </div>
    </div>
    <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
        <div class="text-center text-sm-left mb-2 mb-sm-0">
            <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap" style="color:black; font-weight: 700;">{{ SettingsFacade::data()->title }}
            </h4>
            <p class="mb-0" style="color:black; font-weight: 700;">Phone: {{ SettingsFacade::data()->phone }}</p>
            <p class="mb-0" style="color:black; font-weight: 700;">Email: {{ SettingsFacade::data()->email }}</p>
            <p class="mb-0" style="color:black; font-weight: 700;">Website: {{ SettingsFacade::data()->website }}</p>
        </div>
        <div class="text-center text-sm-right">
            <br>
            <div><button class="btn btn-primary" onclick="this.style.display='none';window.print()">Print</button></div>
            <div style="margin-top: 20px;"><small style="color:black; font-weight: 700;">Address: {{ SettingsFacade::data()->address }}</small></div>
            <div><small style="color:black; font-weight: 700;">City:
                    {{ SettingsFacade::data()->city }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;State:
                    {{ SettingsFacade::data()->state }}</small></div>
            <div><small style="color:black; font-weight: 700;">NTN: {{ SettingsFacade::data()->ntn }}</small></div>
        </div>
    </div>
</div>
