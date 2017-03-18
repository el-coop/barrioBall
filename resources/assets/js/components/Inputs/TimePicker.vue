<template>
    <div class='input-group date'>
        <span class="input-group-addon" v-if="label">
            {{label}}
        </span>
        <input type='text' class="form-control"/>
        <span class="input-group-addon">
            <i class="glyphicon glyphicon-time"></i>
        </span>
    </div>
</template>

<script>
	export default{

		props: {
			label:{
				default: ''
			},
		    minDate: {
		    	default: 'now'
            },
            locale:{
		    	default: 'en'
            }
        },

		mounted(){
			$(this.$el).datetimepicker({
				minDate: this.minDate,
                locale: this.locale,
				format: "HH:mm"
			});
        }
	}
</script>
