<template>
    <div class="text-center" v-if="loading">
        <i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i>
    </div>
    <div v-else>
        <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
        <div v-else>
            <a class="btn btn-primary" href="users/create">Create</a>
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
                                    <a :href="'users/' + user.id + '/edit'" type="button" class="btn btn-primary btn-sm">Edit</a>
                                    <a v-on:click="deleteUser(user.id)" type="button" class="btn btn-danger btn-sm">Delete</a>
                                    <a v-on:click="activateUser(user.id)" type="button" class="btn btn-primary btn-sm">
                                        {{ (user.active ? 'Deactivate' : 'Activate') }}
                                    </a>
                                    <a :href="'users/' + user.id" type="button" class="btn btn-info btn-sm">View</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
            loading: false
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            const th = this;
            this.loading = true;
            axios.get('/users/get/all')
                .then(function(response) {
                    th.users = response.data;
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
                    if(status) {
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
        clearNotifications() {
            this.success = '';
            this.error = '';
        }
    }
}
</script>
