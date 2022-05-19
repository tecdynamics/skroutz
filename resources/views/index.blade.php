@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="flexbox-annotated-section">
        <div class="flexbox-annotated-section-annotation">
            <div class="annotated-section-title pd-all-20">
                <h2>{{ trans('plugins/skroutz::skroutz.title') }}</h2>
            </div>
            <div class="annotated-section-description pd-all-20 p-none-t">
                <p class="color-note">{{ trans('plugins/skroutz::skroutz.description') }}</p>
            </div>
        </div>
        <!--
        'name' => 'Skroutz',
            'settings' => 'Settings',
            'title' => 'Skroutz XML Feeder',
            'enable_skroutz' => 'Enable Skroutz XML Feeder',
            'description' => 'Settings for Skroutz',
            'enable_skroutz_schema_description' => 'Settings for Skroutz',
            'skroutz_reload_time'=>'Χρόνος ανανέωσης XML',
            'skroutz_manufacturer'=>'Κατασκευαστής',
            'skroutz_color'=>'Χρώμα',
            'skroutz_size'=>'Μέγεθος',
            'skroutz_availablility'=>'Διαθεσιμότητα',
            'skroutz_preorder_availablility'=>'Διαθεσιμότητα Προ-παραγγελίας',
            'skroutz_no_availablility'=>'Μη διαθεσιμότητα',
            'skroutz_description'=>'Περιγραφή',
            'skroutz_short_description'=>'Σύντομη περιγραφή',
            'skroutz_last_update'=>' Τελευταία ενημέρωση: ',
            'skroutz_static_shipping'=>'Σταθερός συντελεστής μεταφορικών',
            'skroutz_free_shipping_above'=>'Δωρεάν μεταφορικά πάνω από',
            'skroutz_file_url'=>'Κοινοποιήστε την διεύθυνση του αρχείου XML Feed στο Skroutz.gr ή στο Bestprice.grΚοινοποιήστε την διεύθυνση του αρχείου XML Feed στο Skroutz.gr ή στο Bestprice.gr',
            'skroutz_view_xml' => 'Προβολή αρχείου XML',
            'skroutz_update_xml' => 'Προβολή αρχείου XML',

        -->
        <div class="flexbox-annotated-section-content">
            <div class="wrapper-content pd-all-20">
                <label>
                    {{ trans('plugins/skroutz::skroutz.skroutz_last_update') }} {{date('Y-m-d H:i:s',$last_run)}}
                </label>
                <div class="form-group mb-3">
                    <div class="form-group mb-3 text-center">
                        <a class="btn btn-link" href="{{ $xml_file}}" target="_blank"
                           type="button">{{ trans('plugins/skroutz::skroutz.skroutz_view_xml') }}</a>

                        <a href="{{route('skroutz.runmanual')}}" class="btn btn-success"
                           type="button">{{ trans('plugins/skroutz::skroutz.skroutz_update_xml') }}</a>
                    </div>
                </div>

                <form action="{{$form_url}}" method="post" name="{{SKROUTZ_NAME}}">
                    @csrf

                    <div class="form-group mb-3">
                        <div class="form-group mb-3">
                            <input type="hidden" name="enable_faq_schema" value="0">
                            <label>
                                <input type="checkbox" value="1" @if (setting('skroutz_enable', 0)) checked
                                       @endif name="skroutz_enable">
                                {{ trans('plugins/skroutz::skroutz.enable_skroutz') }}
                            </label>
                            <span
                                class="help-ts">{{ trans('plugins/skroutz::skroutz.enable_skroutz_schema_description') }}</span>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>
                            {{ trans('plugins/skroutz::skroutz.skroutz_reload_time') }}
                        </label>
                        @php
                            $skroutz_update_time = setting('skroutz_update_time', '');
                        @endphp
                        <select name="skroutz_update_time" tabindex="1" aria-hidden="true" class="form-select">
                            <option value="" @if($skroutz_update_time=='')
                                {{'selected="selected"'}}
                                @endif>Επιλέξτε
                            </option>
                            <option value="disabled" @if($skroutz_update_time=='disabled')
                                {{'selected="selected"'}}
                                @endif>{{trans('plugins/skroutz::skroutz.skroutz_disabled')}}</option>
                            <option value="hourly" @if($skroutz_update_time=='hourly')
                                {{'selected="selected"'}}
                                @endif>{{trans('plugins/skroutz::skroutz.skroutz_every_hour')}}</option>
                            <option value="twicedaily" @if($skroutz_update_time=='twicedaily')
                                {{'selected="selected"'}}
                                @endif>{{trans('plugins/skroutz::skroutz.skroutz_twice_day')}}</option>
                            <option value="daily" @if($skroutz_update_time=='daily')
                                {{'selected="selected"'}}
                                @endif>{{trans('plugins/skroutz::skroutz.skroutz_daily')}}</option>
                        </select>

                    </div>
                    <div class="form-group mb-3">
                        <div class="form-group mb-3">
                            @php
                                $avialability = setting('skroutz_availability', '');
                            @endphp
                            <label>
                                {{ trans('plugins/skroutz::skroutz.skroutz_availablility') }}
                            </label>
                            <select name="skroutz_availability" tabindex="2" aria-hidden="true" class="form-select">
                                <option value="" @if($avialability=='')
                                    {{'selected="selected"'}}
                                    @endif>{{ trans('plugins/skroutz::skroutz.skroutz_select') }}</option>
                                <option
                                    value="Άμεση παραλαβή/Παράδoση 1 έως 3 ημέρες" @if($avialability=='Άμεση παραλαβή/Παράδoση 1 έως 3 ημέρες')
                                    {{'selected="selected"'}}
                                    @endif>{{ trans('plugins/skroutz::skroutz.skroutz_asp') }}</option>
                                <option value="Παράδοση σε 1-3 ημέρες" @if($avialability=='Παράδοση σε 1-3 ημέρες')
                                    {{'selected="selected"'}}
                                    @endif>{{ trans('plugins/skroutz::skroutz.skroutz_one_to_three_days') }}</option>
                                <option value="Παράδοση σε 4-10 ημέρες" @if($avialability=='Παράδοση σε 4-10 ημέρες')
                                    {{'selected="selected"'}}
                                    @endif>{{ trans('plugins/skroutz::skroutz.skroutz_four_to_ten_days') }}</option>
                                <option value="Παράδοση έως 30 ημέρες" @if($avialability=='Παράδοση έως 30 ημέρες')
                                    {{'selected="selected"'}}
                                    @endif>{{ trans('plugins/skroutz::skroutz.skroutz_thirtydays') }}</option>

                            </select>

                        </div>
                    </div>
                    <div class="form-group mb-3">
                        @php
                            $skroutz_availability_preorder = setting('skroutz_availability_preorder', '');
                        @endphp
                        <label>
                            {{ trans('plugins/skroutz::skroutz.skroutz_preorder_availablility') }}
                        </label>
                        <select name="skroutz_availability_preorder" tabindex="2" aria-hidden="true"
                                class="form-select">
                            <option @if($skroutz_availability_preorder=='')
                                        {{'selected="selected"'}}
                                    @endif
                                    value=""> {{trans('plugins/skroutz::skroutz.skroutz_select') }} </option>
                            <option @if($skroutz_availability_preorder=='Άμεση παραλαβή/Παράδoση 1 έως 3 ημέρες')
                                        {{'selected="selected"'}}
                                    @endif
                                    value="Άμεση παραλαβή/Παράδoση 1 έως 3 ημέρες">
                                {{trans('plugins/skroutz::skroutz.skroutz_asp') }}
                            </option>
                            <option
                                @if($skroutz_availability_preorder=='Παράδοση σε 1-3 ημέρες')
                                    {{'selected="selected"'}}
                                @endif
                                value="Παράδοση σε 1-3 ημέρες"> {{trans('plugins/skroutz::skroutz.skroutz_one_to_three_days')
                                }}</option>
                            <option
                                @if($skroutz_availability_preorder=='Παράδοση σε 4-10 ημέρες')
                                    {{'selected="selected"'}}
                                @endif
                                value="Παράδοση σε 4-10 ημέρες">{{trans('plugins/skroutz::skroutz.skroutz_four_to_ten_days')
                                }}</option>
                            <option
                                @if($skroutz_availability_preorder=='Παράδοση έως 30 ημέρες')
                                    {{'selected="selected"'}}
                                @endif
                                value="Παράδοση έως 30 ημέρες">
                                {{trans('plugins/skroutz::skroutz.skroutz_thirtydays') }}</option>


                            <option
                                @if($skroutz_availability_preorder=='Απόκρυψη από το XML')
                                    {{'selected="selected"'}}
                                @endif
                                value="Απόκρυψη από το XML">{{trans('plugins/skroutz::skroutz.skroutz_exclude_from_xml') }}</option>


                        </select>

                    </div>

                    <?php
                    if (count(Language::getSupportedLocales()) > 0) {
                    $skroutz_xml_selected_lang = setting('skroutz_xml_selected_lang', '');
                    ?>
                    <div class="form-group mb-3">
                        <label>
                            {{ trans('plugins/skroutz::skroutz.skroutz_lang') }}
                        </label>
                        <select id="skroutz_lang" name="skroutz_xml_selected_lang" tabindex="4"
                                class="form-select" aria-hidden="true">
                            @foreach(Language::getSupportedLocales() as $lang)
                                <option
                                    value="{{$lang['lang_code']}}" @if($skroutz_xml_selected_lang==$lang['lang_code'])
                                    {{'selected="selected"'}}
                                    @endif>{{ $lang['lang_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="form-group mb-3">
                        @php
                            $skroutz_xml_noavailability = setting('skroutz_xml_noavailability', '');
                        @endphp
                        <label>
                            {{ trans('plugins/skroutz::skroutz.skroutz_no_availablility') }}
                        </label>
                        <select id="skroutz_xml_noavailability" name="skroutz_xml_noavailability" tabindex="5"
                                class="form-select" aria-hidden="true">
                            <option
                                value="" @if($skroutz_xml_noavailability=='')
                                {{'selected="selected"'}}
                                @endif>{{ trans('plugins/skroutz::skroutz.skroutz_select') }}</option>
                            <option
                                value="Παράδοση σε 1 - 3 ημέρες" @if($skroutz_xml_noavailability=='Παράδοση σε 1 - 3 ημέρες')
                                {{'selected="selected"'}}
                                @endif>{{ trans('plugins/skroutz::skroutz.skroutz_one_to_three_days') }}</option>
                            <option
                                value="Παράδοση σε 4 - 10 ημέρες" @if($skroutz_xml_noavailability=='Παράδοση σε 4 - 10 ημέρες')
                                {{'selected="selected"'}}
                                @endif>{{ trans('plugins/skroutz::skroutz.skroutz_four_to_ten_days') }}</option>
                            <option
                                value="Παράδοση έως 30 ημέρες" @if($skroutz_xml_noavailability=='Παράδοση έως 30 ημέρες')
                                {{'selected="selected"'}}
                                @endif>{{ trans('plugins/skroutz::skroutz.skroutz_thirtydays') }}</option>
                            <option value="hide" @if($skroutz_xml_noavailability=='hide')
                                {{'selected="selected"'}}
                                @endif>{{ trans('plugins/skroutz::skroutz.skroutz_exclude_from_xml')}}</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        @php
                            $skroutz_xml_desc_field = setting('skroutz_xml_desc_field', '');
                        @endphp
                        <label>
                            {{ trans('plugins/skroutz::skroutz.skroutz_description') }}
                        </label>
                        <div class="form-group mb-3">
                            <label class="px-5"> <input type="radio" name="skroutz_xml_desc_field"
                                                        value="short" @if($skroutz_xml_desc_field=='short')
                                    {{'checked="checked"'}}
                                    @endif>
                                <span>  {{ trans('plugins/skroutz::skroutz.skroutz_short_description') }}</span>
                            </label>
                            <label class="px-5"> <input type="radio" name="skroutz_xml_desc_field"
                                                        value="long" @if($skroutz_xml_desc_field=='long')
                                    {{'checked="checked'}}
                                    @endif>
                                <span>  {{ trans('plugins/skroutz::skroutz.skroutz_description') }}</span> </label>
                        </div>
                    </div>


                    <div class="form-group mb-3">
                        <div class="form-group mb-3 text-center">
                            <button class="btn btn-success"
                                    type="submit">{{ trans('plugins/skroutz::skroutz.skroutz_save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
