<template>
    <div class='input-group date'>
        <span class="input-group-addon" v-if="label">
            {{label}}
        </span>
        <input type='text' class="form-control" :name="name"/>
        <span class="input-group-addon">
            <i class="glyphicon glyphicon-calendar"></i>
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
			},
			name:{
				required: true
			}
        },

		mounted(){
			$(this.$el).datetimepicker({
				minDate: this.minDate,
                locale: this.locale,
				format: "DD/MM/YY"
			});
        }
	}
</script>
