<template>
    <div v-if="info.last_page > 1">
        <ul v-if="info.total > 0" class="pagination">
            <li :class="info.current_page == 1 ? 'disabled' : ''">
                <a @click="(info.current_page == 1 ? null : data(info.current_page - 1))" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li v-if="info.current_page > 1">
                <a @click="data(info.current_page - 1)">{{ info.current_page - 1 }}
                    <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="active">
                <a>{{ info.current_page }}
                    <span class="sr-only">(current)</span>
                </a>
            </li>
            <li v-if="info.current_page < info.last_page">
                <a @click="data(info.current_page + 1)">{{ info.current_page + 1 }}
                    <span class="sr-only">(current)</span>
                </a>
            </li>
            <li :class="info.current_page == info.last_page ? 'disabled' : ''">
                <a @click="(info.current_page == info.last_page ? null : data(info.current_page + 1))" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['info', 'data'],
}
</script>
