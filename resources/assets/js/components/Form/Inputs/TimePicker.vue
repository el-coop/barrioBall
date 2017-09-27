<template>
    <div class='input-group date'>
        <span class="input-group-addon" v-if="label">
            {{label}}
        </span>
        <input type='text' class="form-control" :name="name" :value="initValue"/>
        <span class="input-group-addon">
            <i class="fa fa-clock-o"></i>
        </span>
    </div>
</template>

<script>
	export default{

		props: {
			label:{
				default: ''
			},
            locale:{
		    	default: 'en'
            },
			name:{
				required: true
			},
			initValue: {
				default: null,
				type: String
			}
        },

		mounted(){
			$(this.$el).datetimepicker({
                locale: this.locale,
				format: "HH:mm",
				icons: {
					time: 'fa fa-clock-o',
					date: 'fa fa-calendar',
					up: 'fa fa-chevron-up',
					down: 'fa fa-chevron-down',
					previous: 'fa fa-chevron-left',
					next: 'fa fa-chevron-right',
					today: 'fa fa-crosshairs',
					clear: 'fa fa-trash',
					close: 'fa fa-times'
				},
			});
        }
	}
</script>
