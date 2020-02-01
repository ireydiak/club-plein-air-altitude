<template>
    <div>
        <b-form @submit.prevent="submit">
            <b-form-group
                v-for="(field, value) in model"
                :label-for="value"
                :label="field.label">
                <b-form-input
                    v-model="fields[value]"
                    :id="value"
                    :type="field.type"
                    :name="value"
                    :required="false"
                    v-bind:class="{ 'is-invalid':errors[value] && errors[value].length > 0 }"
                />
                <div
                    class="invalid-feedback"
                    v-if="errors[value] && errors[value].length"
                    v-for="message in errors[value]"
                >
                    {{ message }}
                </div>
            </b-form-group>

            <b-button type="submit" variant="primary">Créer</b-button>
        </b-form>
    </div>

</template>

<script>
    export default {
        name: "UserFormComponent",

        props: {
            model: Object
        },

        data() {
            return {
                errors: {},
                fields: {
                    email: null,
                    firstName: null,
                    lastName: null,
                    password: null,
                    facebook: null,
                    cip: null
                }
            }
        },

        methods: {
            submit() {
                this.$submit(this.$router.users.store, this.fields, this);
            }
        },

        created() {
            for (const key in this.fields) {
                this.errors[key] = [];
            }
        },

    }
</script>

<style scoped>

</style>
