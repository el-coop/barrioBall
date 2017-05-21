<template>
    <span>
        <v-select multiple
                  v-model="values"
                  :debounce="250"
                  :on-search="getOptions"
                  :options="options"
                  placeholder="Insert users names"
                  label="username"
                  ref="select"
        >
        </v-select>
        <input v-for="val in values" type="checkbox" :value="val.id" :name="inputName" checked>
    </span>
</template>
<script>
	import vSelect from 'vue-select'
    export default{

		components: {
		    vSelect
        },

		props: {
			name: {
				type: String,
                required: true,
			},
            action: {
				type: String,
                required: true
            }
		},
		data() {
			return {
				values: [],
				options: [],
                inputName: ''
			}
		},

        methods:{
			getOptions(query,loading){
				loading(true);
                axios.get(this.action + "?query=" + query).then(response => {
                	this.options = response.data;
                    loading(false);
				}, error => {
                	this.options = [];
                	loading(false);
				});
            }
        },

		mounted(){
			this.inputName = this.name + '[]';
		},

	}
</script>
