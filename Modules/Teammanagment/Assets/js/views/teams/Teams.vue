<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading">
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
            <div v-else>
                <a class="btn btn-primary btn-sm" href="teams/create">Create</a>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Tag</tH>
                                <th>Games</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="team in teams">
                                <td>{{ team.name }}</td>
                                <td>{{ team.tag }}</td>
                                <td>
                                    <span style="margin-right: 10px;" v-for="game in team.games">
                                        {{ game.name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="">
                                        <a :href="'teams/' + team.id" type="button" class="btn btn-info btn-sm">View</a>
                                        <a :href="'teams/' + team.id + '/edit'" type="button" class="btn btn-primary btn-sm">Edit</a>
                                        <a v-on:click="deleteTeam(team.id)" type="button" class="btn btn-danger btn-sm">Delete</a>                                    </div>
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
            teams: null,
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
            axios.get('/teammanagment/teams/get/all?page=' + page)
                .then(function(response) {
                    th.teams = response.data.data;
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
        deleteTeam(id) {
            this.clearNotifications();
            const th = this;
            axios.delete('/teammanagment/teams/' + id)
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
