<template>
    <div>
        <b-form @submit.prevent="submit">
            <b-form-group
                v-for="(field, value) in model"
                :label-for="value"
                :label="field.label">
                <b-form-checkbox
                    v-model="fields[value]"
                    v-if="field.type === 'checkbox'"
                    :value="value">
                </b-form-checkbox>
                <b-form-input
                    v-else
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

            <b-button type="submit" variant="primary">{{ buttonText }}</b-button>
        </b-form>
    </div>

</template>

<script>
    export default {
        name: "UserFormComponent",

        props: {
            model: Object,
            member: Object,
            method: String,
            buttonText: String
        },

        computed: {
            url() {
                return this.method === 'store' ?
                    this.$router.members.store :
                    this.$router.members.update.replace('{id}', this.member.memberId);
            }
        },

        data() {
            return {
                errors: {},
                fields: {
                    email: null,
                    firstName: null,
                    lastName: null,
                    password: null,
                    facebookLink: null,
                    cip: null,
                    isPermanent: false,
                    isAdmin: false
                }
            }
        },

        methods: {

            submit() {
                this.resetErrors();

                if (this.method === 'update') {
                    this.fields._method = 'PUT';
                }
                console.log(this.fields);
                this.$submit(this.url, this.fields, this);
            },

            resetErrors() {
                this.errors = {};
            }
        },

        created() {
            for (const key in this.fields) {
                this.errors[key] = [];
            }
        },

        mounted() {
            if (this.member) {
                for (const key in this.member) {
                    if (this.fields.hasOwnProperty(key)) {
                        this.fields[key] = this.member[key];
                    }
                }
            }
        }

    }
</script>

<style scoped>

</style>
