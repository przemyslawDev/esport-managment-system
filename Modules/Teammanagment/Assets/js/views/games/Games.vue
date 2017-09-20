<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading">
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
            <div v-else>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</tH>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="game in games">
                                <td>{{ game.name }}</td>
                                <td>{{ game.slug }}</td>
                                <td>
                                    <div class="">
                                        <a :href="'games/' + game.id" type="button" class="btn btn-info btn-sm">View</a>
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
            games: null,
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
            axios.get('/teammanagment/games/get/all?page=' + page)
                .then(function(response) {
                    th.games = response.data.data;
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
        clearNotifications() {
            this.success = '';
            this.error = '';
        }
    }
}
</script>
