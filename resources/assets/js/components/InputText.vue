<template>
    <div class="form-group">
        <label>{{ name }}</label>
        <input :name="name.toLowerCase()" class="form-control" :value="value" :placeholder="placeholder" @input="$emit('input', $event.target.value)">
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
