<template>
    <div class="text-center" v-if="loading">
        <i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i>
    </div>
    <div v-else>
        <form v-on:submit.prevent>
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" v-model="user.email" placeholder="Email">
            </div>
            <button v-if="sending" class="btn btn-success"><i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i></button>
            <button v-else @click="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            success: '',
            error: '',
            loading: false,
            sending: false,
            user: null,
        }
    },
    mounted() {
        this.getData();
    },
    props: ['id'],
    methods: {
        getData() {
            const th = this;
            this.loading = true;
            axios.get('/users/' + this.id)
                .then(function(response) {
                    th.user = response.data;
                    th.loading = false;
                })
                .catch(function(error) {
                    let r = error.reponse.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                    th.loading = false;
                });
        },
        submit() {
            this.clearNotifications();
            var data = {
                email: this.user.email
            }
            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.put('/users/' + this.id, data)
            .then(function (response) {
                th.sending = false;
                th.success += 'Data updated.'
            })
            .catch(function (error) {
                let r = error.response.data;

                if(r.errors) {
                    th.error += r.errors.email ? r.errors.email + ' ' : '';
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
