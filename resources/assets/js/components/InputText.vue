<template>
    <div class="form-group">
        <label>{{ name }}</label>
        <input class="form-control" v-model="value" :placeholder="placeholder" @input="$emit('input', $event.target.value)">
    </div>
</template>

<script>
export default {
    props: {
        name: '',
        placeholder: '',
        value: ''
    }
}
</script>
