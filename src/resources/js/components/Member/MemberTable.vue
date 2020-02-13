<template>
    <v-container>
        <v-row>
            <v-col sm12>
                <v-card>
                    <v-card-text>
                        <v-data-table
                            ref="table"
                            :items="users"
                            item-key="memberId"
                            :headers="headers"
                            :search="search">
                            <template v-slot:top>
                                <v-toolbar flat color="white">
                                    <v-toolbar-title>Liste des membres</v-toolbar-title>
                                    <v-divider
                                        class="mx-4"
                                        inset
                                        vertical
                                    ></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-text-field
                                        v-model="search"
                                        append-icon="mdi-magnify"
                                        label="Trouver"
                                        single-line
                                        clearable
                                        hide-details/>
                                    <v-dialog v-model="dialog" max-width="700px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark class="mb-2" v-on="on">Ajouter</v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title>
                                                <span class="headline">Ajouter</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-form ref="form" v-model="valid">
                                                            <v-layout wrap>
                                                                <v-flex xs12>
                                                                    <h3>Informations personnelles</h3>
                                                                </v-flex>
                                                                <v-flex xs12 sm12>
                                                                    <v-text-field
                                                                        v-model="editedMember.firstName"
                                                                        label="Prénom"
                                                                        :error-messages="errors.firstName"
                                                                    >
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12>
                                                                    <v-text-field
                                                                        v-model="editedMember.lastName"
                                                                        label="Nom de famille"
                                                                        :error-messages="errors.lastName"
                                                                    >
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12>
                                                                    <v-text-field
                                                                        v-model="editedMember.email"
                                                                        label="Email"
                                                                        :error-messages="errors.email"
                                                                    >
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12>
                                                                    <v-select
                                                                        v-model="editedMember.role"
                                                                        label="Rôle"
                                                                        :items="availableRoles"
                                                                        :error-messages="errors.role"
                                                                    >
                                                                    </v-select>
                                                                </v-flex>
                                                                <v-flex xs12>
                                                                    <h3>Informations de contact</h3>
                                                                </v-flex>
                                                                <v-flex xs12 sm12>
                                                                    <v-text-field
                                                                        v-model="editedMember.phone"
                                                                        :error-messages="errors.phone"
                                                                    >
                                                                        <template v-slot:label>
                                                                            <div>
                                                                                Numéro de téléphone
                                                                                <small>(optionnel)</small>
                                                                            </div>
                                                                        </template>
                                                                    </v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12 sm12>
                                                                    <v-text-field
                                                                        v-model="editedMember.cip"
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
                                                                        v-model="editedMember.facebookLink"
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
                                                        </v-form>
                                                    </v-row>
                                                </v-container>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-spacer/>
                                                <v-btn color="blue darken-1" text @click="closeDialog">Annuler</v-btn>
                                                <v-btn color="blue darken-1" text @click="save">Confirmer</v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>

                                </v-toolbar>

                            </template>
                            <template v-slot:item.action="{ item }">
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="editMember(item)"
                                >
                                    mdi-pencil
                                </v-icon>
                                <v-icon
                                    small
                                    @click="destroy(item)"
                                >
                                    mdi-delete
                                </v-icon>
                            </template>
                        </v-data-table>

                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>

</template>

<script>
    import UserFormComponent from "../UserFormComponent";
    import {MemberModel} from './MemberModel';

    export default {
        name: "MemberTable",
        components: {UserFormComponent},
        props: {
            users: Array
        },

        data: () => {
            return {
                availableRoles: [],
                dialog: false,
                errors: {},
                editedMember: {},
                headers: [
                    {text: 'Prénom', value: 'firstName', sortable: true, filterable: true},
                    {text: 'Nom', value: 'lastName', sortable: true, filterable: true},
                    {text: 'Email', value: 'email'},
                    {text: 'Téléphone', value: 'phone', sortable: false},
                    {text: 'CIP', value: 'cip'},
                    {text: 'Facebook', value: 'facebookLink'},
                    {text: 'Rôle', value: 'role', sortable: true},
                    {text: 'Date de création', value: 'created_at', sortable: true},
                    {text: 'Actions', value: 'action', sortable: false}
                ],
                search: '',
                valid: false,
            }
        },

        methods: {

            closeDialog() {
                this.dialog = false;
                setTimeout(() => {
                    this.editedMember = Object.assign({}, {});
                    this.editedIndex = -1;
                    this.errors = {};
                }, 300);
            },

            destroy(member) {
                let model = new MemberModel(member);
                const index = this.users.indexOf(member);

                return confirm('Désirez-vous vraiment supprimer ce membre?') &&
                    model.delete().then(response => {
                        this.users.splice(index, 1);
                        window.location.reload();
                        this.$toasted.global.api_success(response.response.data.message);
                    }).catch(error => {
                        console.error(error);
                    });
            },

            editMember(user) {
                this.editedIndex = this.users.indexOf(user);
                this.editedMember = Object.assign({}, user);
                this.dialog = true;
            },

            save() {
                let model = new MemberModel(this.editedMember);

                if (this.editedIndex > -1) {
                    this.$http.put('/members/' + this.editedMember.memberId, model.toJSON()).then(response => {
                        Object.assign(this.users[this.editedIndex], response.data.member);
                        this.$toasted.global.api_success(response.data.message);
                        this.closeDialog();
                    }).catch(error => {
                        console.error(error);
                        this.errors = error.data.errors;
                        this.$toasted.global.api_error(error.data.message);
                    });
                } else {
                    this.$http.post('/members/', model.toJSON()).then(response => {
                        this.users.push(response.data.member);
                        this.$toasted.global.api_success(response.data.message);
                        this.closeDialog();
                    }).catch(error => {
                        console.error(error);
                        this.errors = error.data.errors;
                        this.$toasted.global.api_error(error.data.message);
                    });
                }
            }
        },

        mounted() {
            this.availableRoles = (new MemberModel()).availableRoles();
        }
    }
</script>

<style scoped>

</style>
