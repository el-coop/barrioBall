<div class="list-group">
    @foreach([
        __('global/tech.serverSide') => __('global/tech.serverSideDesc'),
        __('global/tech.clientSide') => __('global/tech.clientSideDesc'),
        __('global/tech.design') => __('global/tech.designDesc'),
        __('global/tech.testing') => __('global/tech.testingDesc'),
        __('global/tech.development') => __('global/tech.developmentDesc'),
    ] as $title => $description)
        @component('tech.components.button',[
            'title' => $title,
            'description' => $description
        ])
        @endcomponent
    @endforeach
</div>
