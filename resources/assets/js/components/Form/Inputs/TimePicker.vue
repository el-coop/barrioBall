<template>
    <div class='input-group date'>
        <div class="input-group-prepend input-group-addon" v-if="label">
            <span class="input-group-text" v-html="label"></span>
        </div>
        <input type='text' class="form-control" :name="name" :value="initValue" />
        <div class="input-group-append input-group-addon">
            <span class="input-group-text">
                <i class="fa fa-clock-o"></i></span>
        </div>
    </div>
</template>

<script>
	export default {

		props: {
			label: {
				default: ''
			},
			locale: {
				default: 'en'
			},
			name: {
				required: true
			},
			initValue: {
				default: null,
				type: String
			}
		},

		mounted() {
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
			}).on('dp.show', () => {
				this.$emit('shown');
			}).on('dp.hide', () => {
				this.$emit('hidden');
			});
		}
	}
</script>
