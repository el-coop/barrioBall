<template>
    <div class="flip-container">
        <div class="flipper" :class="{ flipped: flipped}">
            <div class="front" id="">
                <slot name="front"></slot>
            </div>
            <div class="back" id="">
                <slot name="back"></slot>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
    	props: {
    		flipped: {
    			type: Boolean,
                required: true
            }
        }
    }
</script>
