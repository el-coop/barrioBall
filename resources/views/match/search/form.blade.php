<ajax-form class="form-inline justify-content-center" :auto-submit="false"
           v-on:submit-clicked="submit"
           v-on:search-completed="searchResults" v-on:error="showErrors" :errors-box="false"
           btn-wrapper-class="sm-btn-block align-self-baseline"
           ref="form">
    <div class="mb-2 mr-sm-2 w-100-sm-down align-self-baseline"
         :class="{'is-invalid' : errors.hasOwnProperty('date')}"
         id="date_group">
        <date-picker label="@lang('match/search.date'):" name="date"></date-picker>
        <span class="invalid-feedback"
              v-if="errors.hasOwnProperty('to')">* @{{ errors.date[0]}}</span>
    </div>
    <div class="mb-2 mr-sm-2 w-100-sm-down align-self-baseline"
         :class="{'is-invalid' : errors.hasOwnProperty('from')}"
         id="start_time_group">
        <time-picker label="@lang('match/search.from'):" name="from"></time-picker>
        <span class="invalid-feedback"
              v-if="errors.hasOwnProperty('from')">* @{{ errors.from[0] }}</span>
    </div>
    <div class="mb-2 mr-sm-2 w-100-sm-down align-self-baseline"
         :class="{'is-invalid' : errors.hasOwnProperty('to')}"
         id="end_time_group">
        <time-picker label="@lang('match/search.to'):" name="to"></time-picker>
        <span class="invalid-feedback"
              v-if="errors.hasOwnProperty('to')">* @{{ errors.to[0]}}</span>
    </div>
    <i class="fa fa-search" slot="submit"></i>
</ajax-form>
