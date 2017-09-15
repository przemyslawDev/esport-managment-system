<template>
    <div class="text-center" v-if="condition">
        <i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i>
    </div>
</template>

<script>
export default {
    props: ['condition']
}
</script>
