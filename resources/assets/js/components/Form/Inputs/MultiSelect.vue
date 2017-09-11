<template>
    <span>
        <v-select multiple
                  v-model="values"
                  :debounce="250"
                  :on-search="getOptions"
                  :options="options"
                  :placeholder="placeholder"
                  label="username"
                  ref="select"
        >
        </v-select>
        <input v-for="val in values" type="checkbox" :value="val.id" :name="inputName" checked>
    </span>
</template>
<style scoped>
    input[type="checkbox"] {
        display: none;
    }
</style>
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
			},
			placeholder: {
				type: String,
			}
		},
		data() {
			return {
				values: [],
				options: [],
                inputName: this.name + '[]'
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

	}
</script>
