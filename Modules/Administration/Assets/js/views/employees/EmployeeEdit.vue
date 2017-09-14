<template>
    <div class="text-center" v-if="loading">
        <i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i>
    </div>
    <div v-else>
        <form v-on:submit.prevent>
            <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
            <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
            <div class="form-group">
                <label>Firstname</label>
                <input class="form-control" v-model="employee.firstname" placeholder="Firstname">
            </div>
            <div class="form-group">
                <label>Lastname</label>
                <input class="form-control" v-model="employee.lastname" placeholder="Lastname">
            </div>
            <div class="form-group">
                <label>Office</label>
                <input class="form-control" v-model="employee.office" placeholder="Office">
            </div>
            <div class="form-group">
                <label>Birthdate</label>
                <input class="form-control" v-model="employee.birthdate" placeholder="MM-DD-YYYY">
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
            employee: {}
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
            axios.get('/administration/employees/employee/' + this.id)
                .then(function(response) {
                    th.employee = response.data;
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
                firstname: this.employee.firstname,
                lastname: this.employee.lastname,
                office: this.employee.office,
                birthdate: this.employee.birthdate,
            }
            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.put('/administration/employees/' + this.id, data)
            .then(function (response) {
                th.sending = false;
                th.success += 'Data updated.'
            })
            .catch(function (error) {
                let r = error.response.data;

                if(r.errors) {
                    th.error += r.errors.firstname ? r.errors.firstname + ' ' : '';
                    th.error += r.errors.lastname ? r.errors.lastname + ' ' : '';
                    th.error += r.errors.office ? r.errors.office + ' ' : '';
                    th.error += r.errors.birthdate ? r.errors.birthdate + ' ' : '';
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
