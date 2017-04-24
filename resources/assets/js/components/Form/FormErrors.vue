<template>
    <div class="alert alert-danger text-left" v-if="errors">
        <ul v-if="typeof errors == 'object'">
            <li v-for="error in errors">
                {{error[0]}}
            </li>
        </ul>
        <span v-else>
            {{errors}}
        </span>
    </div>
</template>
<script>
	export default{
		props: {
			errors: {
				default: null
			}
		}
	}
</script>
