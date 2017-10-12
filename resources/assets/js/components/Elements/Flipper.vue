<template>
    <div class="flip-container">
        <div class="flipper" :class="{ flipped: flipped}">
            <div class="front">
                <slot name="front"></slot>
            </div>
            <div class="back">
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
