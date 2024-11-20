<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>{{$header ?? ''}}</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-dashboard"></i></a></li>
                        <li class="breadcrumb-item">{{$head}}</li>
                        <li class="breadcrumb-item active">{{$bredcumb}}</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="d-flex flex-row-reverse">
                        <div class="page_action">
                            {{-- <button type="button" class="btn btn-default btn-sm"><i class="fa fa-download"></i> Export Profile</button> --}}
                            <a href="{{route('customers.index')}}" class="btn btn-primary btn-sm"><i
                                        class="fa fa-backward"></i> Back </a>
                        </div>
                        <div class="p-2 d-flex">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="mobile-left">
                        <a class="btn btn-primary toggle-email-nav collapsed" data-toggle="collapse" href="#email-nav"
                           role="button" aria-expanded="false" aria-controls="email-nav">
                            <span class="btn-label"><i class="la la-bars"></i></span>
                            Menu
                        </a>
                    </div>
                    <div class="mail-inbox">
                        <div class="mail-left collapse" id="email-nav">
                            <div class="mail-compose m-b-20">
                                <a href="#" class="btn btn-danger btn-block">Menu</a>
                            </div>
                            <div class="mail-side">
                                <ul class="nav nav-tabs">
                                    @if($customer->signatory_for !="Informal_Groups")
                                        <li><a class=" active show" data-toggle="tab" href="#Home"><i
                                                        class="icon-user"></i>Personal Information</a></li>
                                    @endif
                                    <li><a class="@if($customer->signatory_for =="Informal_Groups") active show @endif"
                                           data-toggle="tab" href="#Account"><i class="icon-book-open"></i>Account
                                            Information</a></li>
                                    @if($customer->signatory_for !="Informal_Groups")
                                        <li><a data-toggle="tab" href="#income"><i class="icon-calculator"></i>Source of
                                                Income</a></li>
                                    @endif
                                    <li><a data-toggle="tab" href="#digital"><i class="icon-globe-alt"></i>Digital
                                            Services</a></li>
                                    @if($customer->signatory_for !="Informal_Groups")
                                        <li><a data-toggle="tab" href="#bank"><i class="icon-home"></i>Bank Information</a>
                                        </li>
                                    @endif
                                    <li><a data-toggle="tab" href="#attachment"><i class="icon-docs"></i>Attachments</a>
                                    </li>
                                    @if ($customer->signatory_for == "guardian" || $customer->signatory_for =="Informal_Groups")
                                        <li><a class="" data-toggle="tab" href="#signatory"><i class="icon-users"></i>Signatories</a>
                                        </li>
                                    @endif
                                    <li><a data-toggle="tab" href="#trans" id="transref"><i class="icon-refresh"></i>Transcaction
                                            history</a></li>
                                    <li><a data-toggle="tab" href="#account_activity"><i class="icon-wrench"></i>Maintanance
                                            History</a></li>
                                    @if(Auth::user()->hasRole('Center') && $customer->is_checker_process == '0')
                                        <li><a data-toggle="tab" href="#process" id="procs"><i
                                                        class="icon-arrow-right"></i>Process Account</a></li>
                                    @endif
                                    @if($customer->is_checker_process == '1')
                                        <li><a data-toggle="tab" href="#approvals"><i class="icon-arrow-down"></i>Approvals</a>
                                        </li>
                                    @endif
                                    @if( $customer->is_checker_process == '0' &&  $customer->aml_risk =='High' && Auth::user()->hasRole('MLRO') || Auth::user()->hasRole('HOB'))
                                        <li><a data-toggle="tab" href="#risk"><i class="icon-wrench"></i>High Risk</a>
                                        </li>
                                    @endif
                                    @if( $customer->is_checker_process == '0' &&  $customer->flgpep =='1'  &&  Auth::user()->hasRole('MLRO') || Auth::user()->hasRole('HOB'))
                                        <li><a data-toggle="tab" href="#pep"><i class="icon-wrench"></i>PEP</a></li>
                                    @endif
                                    @if($customer->is_checker_process == '0' &&  $customer->duplicate =="1"  && Auth::user()->hasRole('Center'))
                                        <li><a data-toggle="tab" href="#duplicate"><i class="icon-wrench"></i>Duplicate</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="mail-right">

                            <div class="card-body">
                                <div class="tab-content">
                                    @if($customer->signatory_for !="Informal_Groups")
                                        <div class="ibox tab-pane show active" id="Home">
                                            @include('customers.accountinfo.personal_info')
                                        </div>
                                    @endif
                                    <!--Account-->
                                    <div class="ibox tab-pane @if($customer->signatory_for =="Informal_Groups") active show @endif"
                                         id="Account">
                                        @include('customers.accountinfo.account_info')
                                    </div>
                                    <!--Income source-->
                                    @if($customer->signatory_for !="Informal_Groups")
                                        <div class="ibox tab-pane" id="income">
                                            @include('customers.accountinfo.income')
                                        </div>
                                    @endif
                                    <!--digital-->
                                    <div class="ibox tab-pane" id="digital">
                                        @include('customers.accountinfo.digital')
                                    </div>
                                    <!--bank-->
                                    @if($customer->signatory_for !="Informal_Groups")
                                        <div class="ibox tab-pane" id="bank">
                                            @include('customers.accountinfo.bank')
                                        </div>
                                    @endif
                                    <!--attachment-->
                                    <div class="ibox tab-pane" id="attachment">
                                        @include('customers.accountinfo.attachments')
                                    </div>
                                    <!--trans-->
                                    <div class="ibox tab-pane" id="trans">
                                        @include('customers.accountinfo.transaction')
                                    </div>
                                    <!--trans-->
                                    @if($customer->signatory_for =="guardian" || $customer->signatory_for =="Informal_Groups" )
                                        <div class="ibox tab-pane" id="signatory">
                                            @include('customers.accountinfo.signatory')
                                        </div>
                                    @endif

                                    <div class="ibox tab-pane" id="account_activity">
                                        @include('customers.accountinfo.account_activity')
                                    </div>

                                    <div class="ibox tab-pane" id="approvals">
                                        @include('customers.accountinfo.history')
                                    </div>

                                    <div class="ibox tab-pane" id="process">
                                        @include('customers.accountinfo.process')
                                    </div>

                                    <div class="ibox tab-pane" id="risk">
                                        @include('maintanence.customer.risk')
                                    </div>
                                    <div class="ibox tab-pane" id="duplicate">
                                        @include('maintanence.customer.duplicate')
                                    </div>
                                    <div class="ibox tab-pane" id="pep">
                                        @include('customers.accountinfo.pep')
                                    </div>

                                </div>
                                <div id="loader"><small>please wait </small></div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>