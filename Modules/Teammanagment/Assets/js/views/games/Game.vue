<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading" class="col-md-offset-3 col-md-6">
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
            <div class="panel panel-default panel-show">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ game.name }}</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 " align="center"> <img alt="Pic" src="http://babyinfoforyou.com/wp-content/uploads/2014/10/avatar-300x300.png" class="img-circle img-responsive"> </div>

                        <div class=" col-md-9 col-lg-9 ">
                            <table class="table table-user-information">
                                <tbody>
                                    <tr>
                                        <td>Slug</td>
                                        <td>{{ game.slug }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!--<a href="#" class="btn btn-primary">My Sales Performance</a>
                                <a href="#" class="btn btn-primary">Team Sales Performance</a>-->
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <!-- -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            error: '',
            loading: '',
            game: {}
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
            axios.get('/teammanagment/games/game/' + this.id)
                .then(function(response) {
                    th.game = response.data;
                    th.loading = false;
                })
                .catch(function(error) {
                    let r = error.reponse.data;
                    th.error += 'Fatal error. ';
                    th.error += r.message ? r.message + ' ' : '';
                    th.loading = false;
                });
        }
    }
}
</script>
