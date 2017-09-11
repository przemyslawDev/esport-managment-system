<template>
    <form v-on:submit.prevent>
        <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
        <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

        <div class="form-group">
            <label>Email</label>
            <input class="form-control" v-model="user.email" placeholder="Email">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" v-model="user.password">
        </div>
        <button v-if="sending" class="btn btn-success"><i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i></button>
        <button v-else @click="submit" class="btn btn-success">Submit</button>
    </form>
</template>

<script>
export default {
    data() {
        return {
            success: '',
            error: '',
            sending: false,
            user: {
                email: '',
                password: '',
            }
        }
    },
    methods: {
        submit() {
            this.clearNotifications();
            var data = {
                email: this.user.email,
                password: this.user.password
            }
            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.post('/users', data)
            .then(function (response) {
                th.sending = false;
                th.success += 'Data created.'
            })
            .catch(function (error) {
                let r = error.response.data;

                if(r.errors) {
                    th.error += r.errors.email ? r.errors.email + ' ' : '';
                    th.error += r.errors.password ? r.errors.password + ' ' : '';
                } else {
                    th.error += 'Fatal error. '
                    th.error += r.message ? r.message + ' ' : '';
                }
                th.sending = false;
            });
        },
        clearNotifications() {
            this.success = '';
            this.error = '';
        }
     }
}
</script>
