<div class="row mb-4">
    <div class="col-12 mt-3">
        @include('match.search.form')
    </div>
</div>
<div class="row mr-md-1">
    @include('match.search.results')
</div>
<div class="row mb-5 mb-md-2" v-if="pages">
    <div class="col-12 d-flex">
        <paginate
                :click-handler="getPage"
                :page-count="pages"
                prev-text="@lang('match/search.prev')"
                next-text="@lang('match/search.next')"
                container-class="pagination mx-auto"
                prev-class="page-item"
                prev-link-class="page-link"
                page-class="page-item"
                page-link-class="page-link"
                next-class="page-item"
                next-link-class="page-link">
        </paginate>
    </div>
</div>
