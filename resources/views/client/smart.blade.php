@extends("layouts.app")
@section("style")
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v2.2') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v2.2') }}" rel="stylesheet" />
    <style>
        .chat-content{
            height: 376px!important;
        }
        .page-breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            display: none !important;
        }
        .breadcrumb-item{
            border-left: 1.5px solid rgb(255 255 255 / 18%);
        }
        .input-group-text {
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media (min-width: 995px) {
            .h100 {
                height: 45vh;
            }
            .h100 .chat-content{
                height: 35vh !important;
            }
        }
        .card {
            margin-bottom:0 !important; 
        }
    </style>
@endsection
@section('title',
    ((UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_first_name_show') ) && $client->smart_data ? ($client->smart_data['first_name'] ?? '') : '') . ' ' .
    ((UserPermission::isSuperAdmin(Auth::user()) || UserPermission::hasPermissionInPipeline(Auth::user(), Auth::user()->pipeline_id, 'field_last_name_show') ) && $client->smart_data ? ($client->smart_data['last_name'] ?? '') : '')
)
<?php
//this code no used anymore
/*
@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="background-color: #0d6efd;margin-bottom:0;border-radius: 0;box-shadow: none !important">
                        <div class="card-body" style="padding-bottom: 5px">
                            <div class="row text-white">
                                <div class="col-md-2 col-6">
                                    <small class="form-label">Smart Name</small>
                                    <h3 class="text-white mb-3">
                                        @if (isset($options['smart_show_first_name']))
                                            {{($client->smart_data ? ($client->smart_data['first_name'] ?? '') : '').' '}}
                                        @endif
                                        @if (isset($options['smart_show_first_name']))
                                            {{($client->smart_data ? ($client->smart_data['last_name'] ?? '') : '')}}
                                        @endif
                                    </h3>
                                    <h6 class="text-warning m-0">
                                        @if ($client->smart_user_id)
                                            Smart Client
                                        @endif
                                    </h6>
                                </div>
                                <div class="col-md-1 col-6">
                                    <small class="form-label">ID</small>
                                    <h4>
                                        <a class="text-white" href="{{ route('client.show', $client->id) }}" target="_blank" rel="noopener noreferrer">{{$client->id}}</a>
                                    </h4>
                                </div>
                                @if ($client->smart_user_id)
                                    <div class="col-md-1 col-6">
                                        <small class="form-label">Smart ID</small>
                                        <h4>
                                            <a class="text-white" href="{{ route('smart.show', $client->id) }}">{{$client->smart_user_id}}</a>
                                        </h4>
                                    </div>
                                @endif
                                @if (isset($options['leads_main_tp']))
                                    <div class="col-md-1 col-6">
                                        <small class="form-label">TP</small>
                                        <h4>
                                            @if ($client->broker_id)
                                                <a class="text-white" href="{{ route('main_tp.show', $client->id) }}">{{$client->broker_id}}</a>
                                            @else
                                                <div class="text-white">
                                                    -
                                                </div>
                                            @endif
                                        </h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('fail'))
                        <div class="alert alert-danger">
                            {{ session('fail') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-primary" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link @if ($tab == 'info') active @endif" data-bs-toggle="tab" href="#show" id="view-tab" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class="bx bx-show font-18 me-1"></i>
                                                    </div>
                                                    <div class="tab-title">Smart Information</div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-3">
                                        <div class="tab-pane fade @if ($tab == 'info') active show @endif" id="show" role="tabpanel">
                                            <form class="row g-3 ajax-form" action="{{ route('smart.update',$client->id) }}" method="POST" name="addform" id="addform" >
                                                @csrf
                                                @method('PUT')
                                                <div class="col-12 text-end">
                                                    @if (isset($options['smart_can_update']))
                                                        <button type="button" id="edit_btn" class="btn p-0" style="background-color: transparent"><i class="text-primary bx bx-edit h5 mb-0"></i></button>
                                                        <a href="{{ route('smart.show', $client->id) }}" type="button" id="cancel_btn" class="btn p-0 d-none" style="background-color: transparent"><i class="text-secondary bx bx-x h5 mb-0"></i></a>
                                                        <button type="submit" id="save_btn" class="btn p-0 d-none" style="background-color: transparent"><i class="text-success bx bx-check h5 mb-0"></i></button>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    @if (isset($options['smart_show_first_name']))
                                                        <div class="input-group">
                                                            <input type="text" @if(isset($options['smart_first_name'])) name="smart_data[first_name]" @endif readonly value="{{ old('smart_data[first_name]',($client->smart_data ? ($client->smart_data['first_name'] ?? '') : '')) }}" class="form-control @if(isset($options['smart_first_name'])) editable @endif" id="first_name" placeholder="First Name" />
                                                        </div>
                                                        @error('smart_data.first_name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mt-0">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    @if (isset($options['smart_show_last_name']))
                                                        <div class="input-group">
                                                            <input type="text" class="form-control @if(isset($options['smart_last_name'])) editable @endif" id="last_name" @if(isset($options['smart_last_name'])) name="smart_data[last_name]" @endif readonly value="{{ old('smart_data[last_name]',($client->smart_data ? ($client->smart_data['last_name'] ?? '') : '')) }}" placeholder="Last Name"/>
                                                        </div>
                                                        @error('smart_data.last_name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="phone1" class="form-label">Primary Number</label>
                                                    @if (isset($options['smart_show_phone1']))
                                                        <div class="input-group">
                                                            <input type="tel" class="form-control @if(isset($options['smart_phone1'])) editable @endif" id="phone1" @if(isset($options['smart_phone1'])) name="smart_data[phone1]" @endif readonly value="{{ old('smart_data[phone1]',($client->smart_data ? ($client->smart_data['phone1'] ?? '') : '')) }}" placeholder="Primary Number"/>
                                                        </div>
                                                        @error('smart_data.phone1')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="phone2" class="form-label">Secondary Number</label>
                                                    @if (isset($options['smart_show_phone2']))
                                                        <div class="input-group">
                                                            <input type="tel" class="form-control @if(isset($options['smart_phone2'])) editable @endif" id="phone2" @if(isset($options['smart_phone2'])) name="smart_data[phone2]" @endif readonly value="{{ old('smart_data[phone2]',($client->smart_data ? ($client->smart_data['phone2'] ?? '') : '')) }}" placeholder="Secondary Number"/>
                                                        </div>
                                                        @error('smart_data.phone2')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email Address</label>
                                                    @if (isset($options['smart_show_email']))
                                                        <div class="input-group">
                                                            <input type="mail" class="form-control @if(isset($options['smart_email'])) editable @endif" id="email" @if(isset($options['smart_email'])) name="smart_data[email]" @endif value="{{ old('smart_data[email]',($client->smart_data ? ($client->smart_data['email'] ?? '') : '')) }}" readonly placeholder="Email Address" />
                                                        </div>
                                                        @error('smart_data.email')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="username" class="form-label">Username</label>
                                                    @if (isset($options['smart_show_username']))
                                                        <div class="input-group">
                                                            <input type="text" class="form-control @if(isset($options['smart_username'])) editable @endif" id="username" @if(isset($options['smart_username'])) name="smart_data[username]" @endif value="{{ old('smart_data[username]',($client->smart_data ? ($client->smart_data['username'] ?? '') : '')) }}" readonly placeholder="Smart Username" />
                                                        </div>
                                                        @error('smart_data.username')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="country" class="form-label">Country</label>
                                                    @if (isset($options['smart_show_country']))
                                                        <div class="input-group">
                                                            <select id="country" class="single-select form-select @if(isset($options['smart_country'])) editable @endif" @if(isset($options['smart_country'])) name="smart_data[country]" @endif disabled>
                                                                <option value="{{old('smart_data[country]',($client->smart_data ? ($client->smart_data['country'] ?? '') : ''))}}" selected>{{old('smart_data[country]',($client->smart_data ? ($client->smart_data['country'] ?? '') : ''))}}</option>
                                                                <option value="Afghanistan">Afghanistan</option>
                                                                <option value="Åland Islands">Åland Islands</option>
                                                                <option value="Albania">Albania</option>
                                                                <option value="Algeria">Algeria</option>
                                                                <option value="American Samoa">American Samoa</option>
                                                                <option value="Andorra">Andorra</option>
                                                                <option value="Angola">Angola</option>
                                                                <option value="Anguilla">Anguilla</option>
                                                                <option value="Antarctica">Antarctica</option>
                                                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                                <option value="Argentina">Argentina</option>
                                                                <option value="Armenia">Armenia</option>
                                                                <option value="Aruba">Aruba</option>
                                                                <option value="Australia">Australia</option>
                                                                <option value="Austria">Austria</option>
                                                                <option value="Azerbaijan">Azerbaijan</option>
                                                                <option value="Bahamas">Bahamas</option>
                                                                <option value="Bahrain">Bahrain</option>
                                                                <option value="Bangladesh">Bangladesh</option>
                                                                <option value="Barbados">Barbados</option>
                                                                <option value="Belarus">Belarus</option>
                                                                <option value="Belgium">Belgium</option>
                                                                <option value="Belize">Belize</option>
                                                                <option value="Benin">Benin</option>
                                                                <option value="Bermuda">Bermuda</option>
                                                                <option value="Bhutan">Bhutan</option>
                                                                <option value="Bolivia">Bolivia</option>
                                                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                                <option value="Botswana">Botswana</option>
                                                                <option value="Bouvet Island">Bouvet Island</option>
                                                                <option value="Brazil">Brazil</option>
                                                                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                                <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                                <option value="Bulgaria">Bulgaria</option>
                                                                <option value="Burkina Faso">Burkina Faso</option>
                                                                <option value="Burundi">Burundi</option>
                                                                <option value="Cambodia">Cambodia</option>
                                                                <option value="Cameroon">Cameroon</option>
                                                                <option value="Canada">Canada</option>
                                                                <option value="Cape Verde">Cape Verde</option>
                                                                <option value="Cayman Islands">Cayman Islands</option>
                                                                <option value="Central African Republic">Central African Republic</option>
                                                                <option value="Chad">Chad</option>
                                                                <option value="Chile">Chile</option>
                                                                <option value="China">China</option>
                                                                <option value="Christmas Island">Christmas Island</option>
                                                                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                                                <option value="Colombia">Colombia</option>
                                                                <option value="Comoros">Comoros</option>
                                                                <option value="Congo">Congo</option>
                                                                <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                                                <option value="Cook Islands">Cook Islands</option>
                                                                <option value="Costa Rica">Costa Rica</option>
                                                                <option value="Cote D'ivoire">Cote D'ivoire</option>
                                                                <option value="Croatia">Croatia</option>
                                                                <option value="Cuba">Cuba</option>
                                                                <option value="Cyprus">Cyprus</option>
                                                                <option value="Czech Republic">Czech Republic</option>
                                                                <option value="Denmark">Denmark</option>
                                                                <option value="Djibouti">Djibouti</option>
                                                                <option value="Dominica">Dominica</option>
                                                                <option value="Dominican Republic">Dominican Republic</option>
                                                                <option value="Ecuador">Ecuador</option>
                                                                <option value="Egypt">Egypt</option>
                                                                <option value="El Salvador">El Salvador</option>
                                                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                                <option value="Eritrea">Eritrea</option>
                                                                <option value="Estonia">Estonia</option>
                                                                <option value="Ethiopia">Ethiopia</option>
                                                                <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                                                <option value="Faroe Islands">Faroe Islands</option>
                                                                <option value="Fiji">Fiji</option>
                                                                <option value="Finland">Finland</option>
                                                                <option value="France">France</option>
                                                                <option value="French Guiana">French Guiana</option>
                                                                <option value="French Polynesia">French Polynesia</option>
                                                                <option value="French Southern Territories">French Southern Territories</option>
                                                                <option value="Gabon">Gabon</option>
                                                                <option value="Gambia">Gambia</option>
                                                                <option value="Georgia">Georgia</option>
                                                                <option value="Germany">Germany</option>
                                                                <option value="Ghana">Ghana</option>
                                                                <option value="Gibraltar">Gibraltar</option>
                                                                <option value="Greece">Greece</option>
                                                                <option value="Greenland">Greenland</option>
                                                                <option value="Grenada">Grenada</option>
                                                                <option value="Guadeloupe">Guadeloupe</option>
                                                                <option value="Guam">Guam</option>
                                                                <option value="Guatemala">Guatemala</option>
                                                                <option value="Guernsey">Guernsey</option>
                                                                <option value="Guinea">Guinea</option>
                                                                <option value="Guinea-bissau">Guinea-bissau</option>
                                                                <option value="Guyana">Guyana</option>
                                                                <option value="Haiti">Haiti</option>
                                                                <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                                                <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                                                <option value="Honduras">Honduras</option>
                                                                <option value="Hong Kong">Hong Kong</option>
                                                                <option value="Hungary">Hungary</option>
                                                                <option value="Iceland">Iceland</option>
                                                                <option value="India">India</option>
                                                                <option value="Indonesia">Indonesia</option>
                                                                <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                                                <option value="Iraq">Iraq</option>
                                                                <option value="Ireland">Ireland</option>
                                                                <option value="Isle of Man">Isle of Man</option>
                                                                <option value="Israel">Israel</option>
                                                                <option value="Italy">Italy</option>
                                                                <option value="Jamaica">Jamaica</option>
                                                                <option value="Japan">Japan</option>
                                                                <option value="Jersey">Jersey</option>
                                                                <option value="Jordan">Jordan</option>
                                                                <option value="Kazakhstan">Kazakhstan</option>
                                                                <option value="Kenya">Kenya</option>
                                                                <option value="Kiribati">Kiribati</option>
                                                                <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                                                <option value="Korea, Republic of">Korea, Republic of</option>
                                                                <option value="Kuwait">Kuwait</option>
                                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                                                <option value="Latvia">Latvia</option>
                                                                <option value="Lebanon">Lebanon</option>
                                                                <option value="Lesotho">Lesotho</option>
                                                                <option value="Liberia">Liberia</option>
                                                                <option value="Libya">Libya</option>
                                                                <option value="Liechtenstein">Liechtenstein</option>
                                                                <option value="Lithuania">Lithuania</option>
                                                                <option value="Luxembourg">Luxembourg</option>
                                                                <option value="Macao">Macao</option>
                                                                <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                                                <option value="Madagascar">Madagascar</option>
                                                                <option value="Malawi">Malawi</option>
                                                                <option value="Malaysia">Malaysia</option>
                                                                <option value="Maldives">Maldives</option>
                                                                <option value="Mali">Mali</option>
                                                                <option value="Malta">Malta</option>
                                                                <option value="Marshall Islands">Marshall Islands</option>
                                                                <option value="Martinique">Martinique</option>
                                                                <option value="Mauritania">Mauritania</option>
                                                                <option value="Mauritius">Mauritius</option>
                                                                <option value="Mayotte">Mayotte</option>
                                                                <option value="Mexico">Mexico</option>
                                                                <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                                                <option value="Moldova, Republic of">Moldova, Republic of</option>
                                                                <option value="Monaco">Monaco</option>
                                                                <option value="Mongolia">Mongolia</option>
                                                                <option value="Montenegro">Montenegro</option>
                                                                <option value="Montserrat">Montserrat</option>
                                                                <option value="Morocco">Morocco</option>
                                                                <option value="Mozambique">Mozambique</option>
                                                                <option value="Myanmar">Myanmar</option>
                                                                <option value="Namibia">Namibia</option>
                                                                <option value="Nauru">Nauru</option>
                                                                <option value="Nepal">Nepal</option>
                                                                <option value="Netherlands">Netherlands</option>
                                                                <option value="Netherlands Antilles">Netherlands Antilles</option>
                                                                <option value="New Caledonia">New Caledonia</option>
                                                                <option value="New Zealand">New Zealand</option>
                                                                <option value="Nicaragua">Nicaragua</option>
                                                                <option value="Niger">Niger</option>
                                                                <option value="Nigeria">Nigeria</option>
                                                                <option value="Niue">Niue</option>
                                                                <option value="Norfolk Island">Norfolk Island</option>
                                                                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                                                <option value="Norway">Norway</option>
                                                                <option value="Oman">Oman</option>
                                                                <option value="Pakistan">Pakistan</option>
                                                                <option value="Palau">Palau</option>
                                                                <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                                                <option value="Panama">Panama</option>
                                                                <option value="Papua New Guinea">Papua New Guinea</option>
                                                                <option value="Paraguay">Paraguay</option>
                                                                <option value="Peru">Peru</option>
                                                                <option value="Philippines">Philippines</option>
                                                                <option value="Pitcairn">Pitcairn</option>
                                                                <option value="Poland">Poland</option>
                                                                <option value="Portugal">Portugal</option>
                                                                <option value="Puerto Rico">Puerto Rico</option>
                                                                <option value="Qatar">Qatar</option>
                                                                <option value="Reunion">Reunion</option>
                                                                <option value="Romania">Romania</option>
                                                                <option value="Russia">Russia</option>
                                                                <option value="Rwanda">Rwanda</option>
                                                                <option value="Saint Helena">Saint Helena</option>
                                                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                                <option value="Saint Lucia">Saint Lucia</option>
                                                                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                                <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                                                <option value="Samoa">Samoa</option>
                                                                <option value="San Marino">San Marino</option>
                                                                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                                <option value="Senegal">Senegal</option>
                                                                <option value="Serbia">Serbia</option>
                                                                <option value="Seychelles">Seychelles</option>
                                                                <option value="Sierra Leone">Sierra Leone</option>
                                                                <option value="Singapore">Singapore</option>
                                                                <option value="Slovakia">Slovakia</option>
                                                                <option value="Slovenia">Slovenia</option>
                                                                <option value="Solomon Islands">Solomon Islands</option>
                                                                <option value="Somalia">Somalia</option>
                                                                <option value="South Africa">South Africa</option>
                                                                <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                                                <option value="Spain">Spain</option>
                                                                <option value="Sri Lanka">Sri Lanka</option>
                                                                <option value="Sudan">Sudan</option>
                                                                <option value="Suriname">Suriname</option>
                                                                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                                <option value="Eswatini">Eswatini</option>
                                                                <option value="Sweden">Sweden</option>
                                                                <option value="Switzerland">Switzerland</option>
                                                                <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                                                <option value="Taiwan">Taiwan</option>
                                                                <option value="Tajikistan">Tajikistan</option>
                                                                <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                                                <option value="Thailand">Thailand</option>
                                                                <option value="Timor-leste">Timor-leste</option>
                                                                <option value="Togo">Togo</option>
                                                                <option value="Tokelau">Tokelau</option>
                                                                <option value="Tonga">Tonga</option>
                                                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                                <option value="Tunisia">Tunisia</option>
                                                                <option value="Turkey">Turkey</option>
                                                                <option value="Turkmenistan">Turkmenistan</option>
                                                                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                                <option value="Tuvalu">Tuvalu</option>
                                                                <option value="Uganda">Uganda</option>
                                                                <option value="Ukraine">Ukraine</option>
                                                                <option value="United Arab Emirates">United Arab Emirates</option>
                                                                <option value="United Kingdom">United Kingdom</option>
                                                                <option value="United States">United States</option>
                                                                <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                                                <option value="Uruguay">Uruguay</option>
                                                                <option value="Uzbekistan">Uzbekistan</option>
                                                                <option value="Vanuatu">Vanuatu</option>
                                                                <option value="Venezuela">Venezuela</option>
                                                                <option value="Vietnam">Vietnam</option>
                                                                <option value="Virgin Islands, British">Virgin Islands, British</option>
                                                                <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                                                <option value="Wallis and Futuna">Wallis and Futuna</option>
                                                                <option value="Western Sahara">Western Sahara</option>
                                                                <option value="Yemen">Yemen</option>
                                                                <option value="Zambia">Zambia</option>
                                                                <option value="Zimbabwe">Zimbabwe</option>
                                                            </select>
                                                        </div>
                                                        @error('smart_data.country')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="amount" class="form-label">Amount</label>
                                                    @if (isset($options['smart_show_amount']))
                                                        <div class="input-group">
                                                            <input type="number" step="any" class="form-control @if(isset($options['smart_amount'])) editable @endif" readonly id="amount"  @if(isset($options['smart_amount'])) name="smart_data[amount]" @endif value="{{ old('smart_data[amount]',($client->smart_data ? ($client->smart_data['amount'] ?? '') : '')) }}" placeholder="Amount" />
                                                        </div>
                                                        @error('smart_data.amount')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="bonus" class="form-label">Bonus</label>
                                                    @if (isset($options['smart_show_bonus']))
                                                        <div class="input-group">
                                                            <input type="number" step="any" class="form-control @if(isset($options['smart_bonus'])) editable @endif" readonly id="bonus" @if(isset($options['smart_bonus'])) name="smart_data[bonus]" @endif value="{{ old('smart_data[bonus]',($client->smart_data ? ($client->smart_data['bonus'] ?? '') : '')) }}" placeholder="Bonus" />
                                                        </div>
                                                        @error('smart_data.bonus')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="password" class="form-label">Password</label>
                                                    @if (isset($options['smart_show_pass']))
                                                        <div class="input-group">
                                                            <input type="text" class="form-control password @if(isset($options['smart_pass'])) editable @endif" id="password" readonly @if(isset($options['smart_pass'])) name="smart_data[password]" @endif value="{{ old('smart_data[password]',($client->smart_data ? ($client->smart_data['password'] ?? '******') : '******')) }}"/>
                                                            <button class="btn d-none generate-password" style="border:1px solid #ced4da;" title="Generate Password" type="button"><i class='bx bx-reset'></i></button>
                                                        </div>
                                                        @error('smart_data.password')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        <div>
                                                            ******
                                                        </div>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (isset($options['smart_cards_comments']))
                    <div class="col-lg-3 col-md-6 col-12 mt-2">
                        @include("client.comments",['client' => $client,'comments' => $comments,'add' => isset($options['smart_add_comments']), 'update' => isset($options['smart_update_comments']), 'delete' => isset($options['smart_delete_comments'])])
                    </div>
                @endif
                @if (isset($options['smart_cards_actions']))
                    <div class="col-lg-2 col-md-6 col-12 mt-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <div>
                                        <h4>Actions</h4>
                                    </div>
                                    <div class=" @if ($online == true) text-success @else text-warning @endif online">
                                        @if ($online == true) Online now @else Offline now @endif
                                    </div>
                                </div>
                                <hr class="my-3" />
                                <div class="row text-start">
                                    @if ($client->email && $client->smart_user_id && isset($options['smart_actions_send_email']))
                                        <div class="col-12">
                                            <button type="button" class="btn btn-sm text-primary" data-bs-toggle="modal" data-bs-target="#emailModel" style="background-color: transparent">Send Email</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (isset($options['smart_actions_send_email']))
                            <div class="modal fade" id="emailModel" tabindex="-1" aria-labelledby="emailLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="emailLabel">Send Email</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <ul class="nav nav-tabs nav-primary" role="tablist">
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link active" data-bs-toggle="tab" href="#newEmail" id="view-tab" role="tab" aria-selected="true">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="tab-icon"><i class="bx bx-mail-send font-18 me-1"></i>
                                                                    </div>
                                                                    <div class="tab-title">Send New</div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#emailHistory" id="view-tab" role="tab" aria-selected="true">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="tab-icon"><i class="bx bx-time font-18 me-1"></i>
                                                                    </div>
                                                                    <div class="tab-title">History</div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content py-3">
                                                        <div class="tab-pane fade active show" id="newEmail" role="tabpanel">
                                                            @if ($client->email && $client->smart_user_id && isset($client->smart_data['password']) && $client->smart_data['password'] != '******' && !empty($client->smart_data['password']))
                                                                <div class="row align-items-center">
                                                                    <div class="col">
                                                                        Login Email
                                                                    </div>
                                                                    <div class="col text-primary">
                                                                        @if ($client->smart_user_id)
                                                                            info@smarttraderx.co.uk
                                                                        @endif
                                                                    </div>
                                                                    <div class="col justify-content-end d-flex">
                                                                        <form action="{{ route('email.send', ['id' => $client->id , 'type' => 'login']) }}" method="POST">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-primary my-2">Send</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                            @endif
                                                            @if ($client->email && $client->smart_user_id)
                                                                <form action="{{ route('email.send', ['id' => $client->id , 'type' => 'ftd']) }}" method="POST">
                                                                    @csrf
                                                                    <div class="row align-items-center">
                                                                        <div class="col">
                                                                            FTD Email
                                                                        </div>
                                                                        <div class="col text-primary">
                                                                            @if ($client->smart_user_id)
                                                                                info@smarttraderx.co.uk
                                                                            @endif
                                                                        </div>
                                                                        <div class="col">
                                                                            <input type="text" name="amount" class="form-control" placeholder="Amount" required />
                                                                        </div>
                                                                        <div class="col justify-content-end d-flex">
                                                                            <button type="submit" class="btn btn-sm btn-primary my-2">Send</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            @endif
                                                        </div>
                                                        <div class="tab-pane fade" id="emailHistory" role="tabpanel">
                                                            @foreach ($email_logs as $email_log)
                                                                <div class="row align-items-center">
                                                                    <div class="col">
                                                                        {{$email_log->created_at}}
                                                                    </div>
                                                                    <div class="col">
                                                                        {{$email_log->user->username}}
                                                                    </div>
                                                                    <div class="col">
                                                                        {{$email_log->type}}
                                                                    </div>
                                                                    <div class="col">
                                                                        <a href="{{ route('email.preview', ['id' => $email_log->id]) }}" class="btn btn-sm w-auto" style="background-color: transparent">
                                                                            <i class="bx bx-receipt font-18 text-primary"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="col justify-content-end d-flex">
                                                                        <form action="{{ route('email.send', ['id' => $client->id , 'type' => $email_log->type]) }}" method="POST">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-danger">Resend</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                        
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailModel" tabindex="-1" aria-labelledby="emailLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailLabel">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#newEmail" id="view-tab" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-mail-send font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Send New</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#emailHistory" id="view-tab" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-time font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">History</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content py-3">
                                <div class="tab-pane fade active show" id="newEmail" role="tabpanel">
                                    @if ($client->email && $client->smart_user_id && $client->smart_user_name && $client->password)
                                        <div class="row align-items-center">
                                            <div class="col">
                                                Login Email
                                            </div>
                                            <div class="col text-primary">
                                                @if ($client->smart_user_id)
                                                    info@smarttraderx.co.uk
                                                @endif
                                            </div>
                                            <div class="col justify-content-end d-flex">
                                                <form action="{{ route('email.send', ['id' => $client->id , 'type' => 'login']) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary my-2">Send</button>
                                                </form>
                                            </div>
                                        </div>
                                        <hr>
                                    @endif
                                    @if ($client->email && $client->smart_user_id)
                                        <form action="{{ route('email.send', ['id' => $client->id , 'type' => 'ftd']) }}" method="POST">
                                            @csrf
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    FTD Email
                                                </div>
                                                <div class="col text-primary">
                                                    @if ($client->smart_user_id)
                                                        info@smarttraderx.co.uk
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="amount" class="form-control" placeholder="Amount" required />
                                                </div>
                                                <div class="col justify-content-end d-flex">
                                                    <button type="submit" class="btn btn-sm btn-primary my-2">Send</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="emailHistory" role="tabpanel">
                                    @foreach ($email_logs as $email_log)
                                        <div class="row align-items-center">
                                            <div class="col">
                                                {{$email_log->created_at}}
                                            </div>
                                            <div class="col">
                                                {{$email_log->user->username}}
                                            </div>
                                            <div class="col">
                                                {{$email_log->type}}
                                            </div>
                                            <div class="col">
                                                <a href="{{ route('email.preview', ['id' => $email_log->id]) }}" class="btn btn-sm w-auto" style="background-color: transparent">
                                                    <i class="bx bx-receipt font-18 text-primary"></i>
                                                </a>
                                            </div>
                                            <div class="col justify-content-end d-flex">
                                                <form action="{{ route('email.send', ['id' => $client->id , 'type' => $email_log->type]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">Resend</button>
                                                </form>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                                
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v2.2') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v2.2') }}"></script>
    <script src="{{ url('assets/js/new.min.js?v2.2') }}"></script>
    @if (isset($options['smart_can_update']))
        <script>
            $('#edit_btn').on('click', function() {
                $('.editable').removeAttr('readonly disabled');
                $('#edit_btn').addClass('d-none');
                $('.generate-password').removeClass('d-none');
                $('#cancel_btn, #save_btn').removeClass('d-none');
            });
        </script>
    @endif
@endsection
 * 
 */
?>