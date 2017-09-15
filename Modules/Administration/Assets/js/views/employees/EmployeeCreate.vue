<template>
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
</template>

<script>
export default {
    data() {
        return {
            success: '',
            error: '',
            sending: false,
            employee: {
                firstname: '',
                lastname: '',
                office: '',
                birthdate: ''
            }
        }
    },
    methods: {
        submit() {
            this.clearNotifications();
            var data = {
                firstname: this.employee.firstname,
                lastname: this.employee.lastname,
                office: this.employee.office,
                birthdate: this.employee.birthdate
            }
            this.makeRequest(data);
        },
        makeRequest(data) {
            const th = this;
            this.sending = true
            axios.post('/administration/employees', data)
                .then(function(response) {
                    th.sending = false;
                    th.success += 'Data created.'
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
