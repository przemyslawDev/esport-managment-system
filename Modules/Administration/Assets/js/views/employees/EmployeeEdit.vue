<template>
    <div>
        <spinner :condition="loading"></spinner>
        <div v-if="!loading">
            <form v-on:submit.prevent>
                <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
                <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

                <input-text :name="'Firstname'" v-model="employee.firstname" :placeholder="'Firstname'"></input-text>
                <input-text :name="'Lastname'" v-model="employee.lastname" :placeholder="'Lastname'"></input-text>
                <input-text :name="'Office'" v-model="employee.office" :placeholder="'Office'"></input-text>
                <div class="form-group">
                    <label>Birthdate</label>
                    <datepicker v-model="employee.birthdate" :format="'MM-dd-yyyy'" :bootstrapStyling="true" 
                    :calendar-button="true" :calendar-button-icon="'fa fa-calendar'"></datepicker>
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
                .then(function(response) {
                    th.sending = false;
                    th.success += 'Data updated.'
                })
                .catch(function(error) {
                    let r = error.response.data;

                    if (r.errors) {
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
