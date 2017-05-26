<template>
    <ul v-if="tablePagination && tablePagination.last_page > 1" :class="css.wrapperClass">
        <li class="page-item" :class="[isOnFirstPage ? css.disabledClass : '']">
            <a @click="loadPage(1)" class="page-link">
                <i v-if="css.icons.first != ''" :class="[css.icons.first]"></i>
                <span v-else>&laquo;</span>
            </a>
        </li>
        <li class="page-item" :class="[isOnFirstPage ? css.disabledClass : '']">
            <a @click="loadPage('prev')" class="page-link">
                <i v-if="css.icons.next != ''" :class="[css.icons.prev]"></i>
                <span v-else>&nbsp;&lsaquo;</span>
            </a>
        </li>
        <template v-if="notEnoughPages">
            <template v-for="n in totalPage">
                <li class="page-item" :class="[isCurrentPage(n) ? css.activeClass : '']">
                    <a @click="loadPage(n)" class="page-link"
                       v-html="n">
                    </a>
                </li>
            </template>
        </template>
        <template v-else>
            <template v-for="n in windowSize">
                <li class="page-item" :class="[isCurrentPage(windowStart+n-1) ? css.activeClass : '']">
                    <a @click="loadPage(windowStart+n-1)" class="page-link"
                       v-html="windowStart+n-1">
                    </a>
                </li>
            </template>
        </template>

        <li class="page-item" :class="[isOnLastPage ? css.disabledClass : '']">
            <a @click="loadPage('next')" class="page-link">
                <i v-if="css.icons.next != ''" :class="[css.icons.next]"></i>
                <span v-else>&rsaquo;&nbsp;</span>
            </a>
        </li>
        <li class="page-item" :class="[isOnLastPage ? css.disabledClass : '']">
            <a @click="loadPage(totalPage)" class="page-link">
                <i v-if="css.icons.last != ''" :class="[css.icons.last]"></i>
                <span v-else>&raquo;</span>
            </a>
        </li>
    </ul>
</template>

<script>
	import VuetablePaginationMixin from 'vuetable-2/src/components/VuetablePaginationMixin.vue';

	export default {
		mixins: [VuetablePaginationMixin],
	}
</script>
