<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading">
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
            <div v-else>
                <a class="btn btn-primary btn-sm" href="users/create">Create</a>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Roles</tH>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users">
                                <td>{{ user.email }}</td>
                                <td>
                                    <span style="margin-right: 10px;" v-for="role in user.roles">
                                        {{ role.display_name }}
                                    </span>
                                </td>
                                <td>{{ user.active }}</td>
                                <td>
                                    <div class="">
                                        <a :href="'users/' + user.id" type="button" class="btn btn-info btn-sm">View</a>
                                        <a :href="'users/' + user.id + '/edit'" type="button" class="btn btn-primary btn-sm">Edit</a>
                                        <a v-on:click="deleteUser(user.id)" type="button" class="btn btn-danger btn-sm">Delete</a>
                                        <a v-on:click="activateUser(user.id, user.active)" type="button" class="btn btn-primary btn-sm">
                                            {{ (user.active ? 'Deactivate' : 'Activate') }}
                                        </a>
                                        <a v-on:click="resetPassword(user.id)" type="button" class="btn btn-warning btn-sm">Reset Password</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <pagination :info="pagination" :data="getData"></pagination>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            success: '',
            error: '',
            users: null,
            loading: false,
            pagination: {
                current_page: null,
                total: null,
                per_page: null,
                last_page: null,
            }
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData(page = 1) {
            const th = this;
            this.loading = true;
            axios.get('/users/get/all?page=' + page)
                .then(function(response) {
                    th.users = response.data.data;
                    th.pagination.total = response.data.total;
                    th.pagination.per_page = response.data.per_page;
                    th.pagination.last_page = response.data.last_page;
                    th.pagination.current_page = response.data.current_page;
                    th.loading = false;
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                    th.loading = false;
                });
        },
        deleteUser(id) {
            this.clearNotifications();
            const th = this;
            axios.delete('/users/' + id)
                .then(function(response) {
                    th.getData();
                    th.success += 'Data deleted.';
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                });
        },
        activateUser(id, status) {
            this.clearNotifications();
            const th = this;
            axios.get('/users/activate/' + id)
                .then(function(response) {
                    th.getData();
                    if (status) {
                        th.success += 'The user has been deactivated.';
                    } else {
                        th.success += 'The user has been activated.';
                    }
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                });
        },
        resetPassword(id) {
            this.clearNotifications();
            const th = this;
            axios.get('/users/password/reset/' + id)
                .then(function(response) {
                    console.log(response.data);
                    th.getData();
                    th.success += "Password changed.";
                    th.success += "New password: " + response.data;
                })
                .catch(function(error) {
                    let r = error.response.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                });
        },
        clearNotifications() {
            this.success = '';
            this.error = '';
        }
    }
}
</script>
