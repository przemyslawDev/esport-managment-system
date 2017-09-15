<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading">
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
            <div v-else>
                <a class="btn btn-primary btn-sm" href="employees/create">Create</a>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Firstname</th>
                                <th>Lastname</tH>
                                <th>Email</th>
                                <th>Office</th>
                                <th>Birthdate</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="employee in employees">
                                <td>{{ employee.firstname }}</td>
                                <td>{{ employee.lastname }}</td>
                                <template>
                                    <td v-if="employee.user">{{ employee.user.email }}</td>
                                    <td v-else></td>
                                </template>
                                <td>{{ employee.office }}</td>
                                <td>{{ employee.birthdate }}</td>
                                <td>
                                    <div class="">
                                        <a :href="'employees/' + employee.id" type="button" class="btn btn-info btn-sm">View</a>
                                        <a :href="'employees/' + employee.id + '/edit'" type="button" class="btn btn-primary btn-sm">Edit</a>
                                        <a v-on:click="deleteEmployee(employee.id)" type="button" class="btn btn-danger btn-sm">Delete</a>
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
            employees: null,
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
            axios.get('/administration/employees/get/all?page=' + page)
                .then(function(response) {
                    th.employees = response.data.data;
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
        deleteEmployee(id) {
            this.clearNotifications();
            const th = this;
            axios.delete('/administration/employees/' + id)
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
        clearNotifications() {
            this.success = '';
            this.error = '';
        }
    }
}
</script>
