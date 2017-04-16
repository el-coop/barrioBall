<template>
    <div class='input-group date'>
        <span class="input-group-addon" v-if="label">
            {{label}}
        </span>
        <input type='text' class="form-control" :name="name"/>
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
            locale:{
		    	default: 'en'
            },
			name:{
				required: true
			}
        },

		mounted(){
			$(this.$el).datetimepicker({
                locale: this.locale,
				format: "HH:mm"
			});
        }
	}
</script>
