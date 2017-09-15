<template>
    <button v-if="condition" class="btn btn-success">
        <i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i>
    </button>
    <button v-else v-on:click="submit" class="btn btn-success">Submit</button>
</template>

<script>
export default {
    props: ['condition', 'submit']
}
</script>
