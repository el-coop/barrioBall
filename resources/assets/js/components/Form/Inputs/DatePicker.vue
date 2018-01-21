<template>
    <div class='input-group date'>
        <div class="input-group-prepend input-group-addon" v-if="label">
            <span class="input-group-text" v-html="label"></span>
        </div>
        <input type='text' class="form-control" :name="name" :value="initValue" />
        <div class="input-group-append input-group-addon">
            <span class="input-group-text">
                <i class="fa fa-calendar"></i></span>
        </div>
    </div>
</template>

<script>
	export default {

		props: {
			label: {
				default: ''
			},
			minDate: {
				default() {
					return moment().startOf('day');
				}
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
				minDate: this.minDate,
				locale: this.locale,
				format: "DD/MM/YY",
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
			if (this.initValue) {
				$(this.$el).data('DateTimePicker').date(this.initValue);
			}
		}
	}
</script>
