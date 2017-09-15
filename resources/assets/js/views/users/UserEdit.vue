<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading">
            <form v-on:submit.prevent>
                <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
                <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

                <input-text :name="'Email'" v-model="user.email" :placeholder="'Email'"></input-text>
                <div v-if="!loading_roles" class="form-group">
                    <label>Roles:</label>
                    <select multiple class="form-control" v-model="roles_ids">
                        <option v-for="role in roles" :value="role.id">{{ role.display_name }}</option>
                    </select>
                </div>

                <spinner-button :condition="sending" :submit="submit"></spinner-button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            success: '',
            error: '',
            loading: false,
            loading_roles: false,
            sending: false,
            user: {},
            roles_ids: [],
            roles: null,
        }
    },
    watch: {
        user: function(user) {
            var array = [];
            user.roles.forEach(function(role) {
                array.push(role.id);
            }, this);
            this.roles_ids = array;
        }
    },
    mounted() {
        this.getData();
        this.getRoles();
    },
    props: ['id'],
    methods: {
        getRoles() {
            const th = this;
            this.loading_roles = true;
            axios.get('/roles/get/all')
                .then(function(response) {
                    th.roles = response.data;
                    th.loading_roles = false;
                })
                .catch(function(error) {
                    let r = error.reponse.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                    th.loading_roles = false;
                });
        },
        getData() {
            const th = this;
            this.loading = true;
            axios.get('/users/user/' + this.id)
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
                email: this.user.email,
                roles: this.roles_ids
            }
            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.put('/users/' + this.id, data)
                .then(function(response) {
                    th.sending = false;
                    th.success += 'Data updated.'
                })
                .catch(function(error) {
                    let r = error.response.data;

                    if (r.errors) {
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
