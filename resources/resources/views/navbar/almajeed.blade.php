@php
    use App\Models\MenuRights;
    use App\Models\RightsLevel1;

    $rightLevels = RightsLevel1::with([
        'right_level2' => function ($query) {
            $query->with(['right_level3' => function($query){
                $query->Orderby('code', 'asc');
            }]);
        },
    ])
        ->orderBy('code')
        ->get();

@endphp
<ul class="navbar-nav">
    <li class="nav-item active">
        <a class="nav-link" href="{{ URL::to('/home') }}"><span class="active-item-here"></span><i
                class="fa fa-dashboard mr-5"></i>
            <span>DASHBOARD</span></a>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link text-white" href="{{ URL::to('customer-ledger-account') }}">
            <i class="fa fa-book mr-5"></i>
            <span>CUSTOMER LEDGER ACCOUNT</span>
        </a>
    </li> --}}
    @foreach ($rightLevels as $right1)
        @php
            $MenuRights = MenuRights::where('user_id', Auth::User()->id)
                ->whereType('Level 1')
                ->where('level_id', $right1->id)
                ->count();
            $i = 0;
        @endphp
        @if ($MenuRights > 0)
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="javascript:void(0)" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"> {{ $right1->title }}
                </a>
                <ul class="dropdown-menu multilevel scale-up-left">
                    @foreach ($right1->right_level2 as $right2)
                        @php
                            $MenuRights2 = MenuRights::where('user_id', Auth::User()->id)
                                ->whereType('Level 2')
                                ->where('level_id', $right2->id)
                                ->count();
                        @endphp
                        @if ($MenuRights2 > 0)
                            @if (count($right2->right_level3) > 0)
                                <li class="nav-item dropdown"
                                    @if ($i == 0) style="padding-top: 15px;" @endif
                                    @php $i++; @endphp>
                                    <a class="nav-link dropdown-item dropdown-toggle" href="#">
                                        {{ $right2->title }} &emsp;</a>

                                    <ul class="dropdown-menu"  style="width: 250px;">
                                        @foreach ($right2->right_level3 as $right3)
                                            @php
                                                $MenuRights3 = MenuRights::where('user_id', Auth::User()->id)
                                                    ->whereType('Level 3')
                                                    ->where('level_id', $right3->id)
                                                    ->count();
                                            @endphp
                                            @if ($MenuRights3 > 0)
                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                        href="{{ URL::to($right3->url) }}">{{ $right3->title }}&emsp;</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item" @if ($i == 0) style="padding-top: 15px;" @endif
                                    @php $i++; @endphp>
                                    <a class="nav-link" href="{{ URL::to($right2->url) }}">{{ $right2->title }}
                                        &emsp;</a>
                                </li>
                            @endif
                            {{-- @else
                            <li class="nav-item" @if ($i == 0) style="padding-top: 15px;" @endif
                                @php $i++; @endphp>
                                <a class="nav-link" href="{{ URL::to($right2->url) }}">{{ $right2->title }}</a>
                            </li> --}}
                        @endif
                    @endforeach
                </ul>
            </li>
            {{-- @else
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ URL::to($right1->url) }}">{{ $right1->title }}</a>
            </li> --}}
        @endif
    @endforeach
</ul>
