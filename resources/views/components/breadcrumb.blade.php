<div>

    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">{{ $title }}</h5>
                    </div>

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb">
                        @if(roleName() == 'DEO')
                            <li class="breadcrumb-item"><a href="{{ route('nadra-verification.getDistrictsWiseStats') }}"><i
                                class="feather icon-home"></i></a></li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                class="feather icon-home"></i></a></li>
                        @endif
                        
                        @if (!empty($breadcrumbs))
                            @foreach ($breadcrumbs as $breadcrumb)
                                @if (@$breadcrumb['allow'])
                                    <li class="breadcrumb-item">
                                        <a href="{{ $breadcrumb['link'] }}">{{ $breadcrumb['name'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        @endif
                    </ul>
                    <!--end::Breadcrumb-->


                    @if (@$button['allow'])
                        <a href="{{ $button['link'] }}" style="margin-top: -25px;"
                            class="btn btn-primary font-weight-bold btn-sm px-4 font-size-base ml-2 float-end {{ @$btn['class'] }}">{{ $button['name'] }}</a>
                    @endif

                    @if (@$buttons)
                        @foreach ($buttons as $btn)
                            @if (@$btn['allow'])
                                <a 
                                    href="{{ $btn['link'] }}" 
                                    target="{{ (@$btn['target']) ? $btn['target'] : '' }}" 
                                    style="margin-top: -25px;"
                                    class="btn btn-primary font-weight-bold btn-sm px-4 font-size-base me-2 float-end {{ @$btn['class'] }}">{{ $btn['name'] }}</a>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
