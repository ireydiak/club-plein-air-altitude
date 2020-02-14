<template>

    <v-form ref="form" v-model="valid">
        <v-layout wrap>
            <v-flex xs12>
                <h3>Informations personnelles</h3>
            </v-flex>
            <v-flex xs12 sm12>
                <v-text-field
                    v-model="memberModel.firstName"
                    label="Prénom"
                    :error-messages="errors.firstName"
                >
                </v-text-field>
            </v-flex>
            <v-flex xs12 sm12>
                <v-text-field
                    v-model="memberModel.lastName"
                    label="Nom de famille"
                    :error-messages="errors.lastName"
                >
                </v-text-field>
            </v-flex>
            <v-flex xs12 sm12>
                <v-text-field
                    v-model="memberModel.email"
                    label="Email"
                    :error-messages="errors.email"
                >
                </v-text-field>
            </v-flex>
            <v-flex xs12 sm12>
                <v-select
                    v-model="memberModel.role"
                    label="Rôle"
                    key="role_id"
                    :items="roles"
                    item-text="name"
                    item-value="role_id"
                    :error-messages="errors.role"
                >
                </v-select>
            </v-flex>
            <v-flex xs12>
                <h3>Informations de contact</h3>
            </v-flex>
            <v-flex xs12 sm12>
                <v-text-field
                    v-model="memberModel.phone"
                    :error-messages="errors.phone"
                >
                    <template v-slot:label>
                        <div>
                            Numéro de téléphone <small>(optionnel)</small>
                        </div>
                    </template>
                </v-text-field>
            </v-flex>
            <v-flex xs12 sm12>
                <v-text-field
                    v-model="memberModel.cip"
                    :error-messages="errors.cip"
                >
                    <template v-slot:label>
                        <div>
                            CIP <small>(optionnel)</small>
                        </div>
                    </template>
                </v-text-field>
            </v-flex>
            <v-flex xs12 sm12>
                <v-text-field
                    v-model="memberModel.facebookLink"
                    :error-messages="errors.facebookLink"
                >
                    <template v-slot:label>
                        <div>
                            Facebook <small>(optionnel)</small>
                        </div>
                    </template>
                </v-text-field>
            </v-flex>
        </v-layout>

        <v-row>
            <v-col
                class="d-flex"
                cols="12"
                md="4"
                sm="12"
            >
                <v-btn
                    color="success"
                    @click="submit">
                    {{ buttonText }}
                </v-btn>
            </v-col>
        </v-row>
    </v-form>
</template>

<script>
    import {MemberModel} from './Member/MemberModel';

    export default {
        name: "UserFormComponent",

        props: {
            member: Object,
            buttonText: String,
            title: String,
            roles: Array
        },

        data() {
            return {
                valid: false,
                errors: {},
                memberModel: new MemberModel(),
            }
        },

        methods: {

            setMember(member) {
                if (member) {
                    this.memberModel = new MemberModel(member);
                }
            },

            update() {
                this.$updateModel(this.memberModel, this).then(member => {
                    this.$eventBus.$emit('member-updated', member);
                });
            },

            submit() {
                this.$submitModel(this.memberModel, this);
            },

            resetErrors() {
                this.errors = {};
            }
        },

        mounted() {
            this.setMember(this.member);
        },

    }
</script>
