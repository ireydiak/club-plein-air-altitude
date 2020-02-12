<template>
    <v-card>
        <v-card-title>Liste des membres</v-card-title>
        <v-card-text>
            <v-data-table
                :items="users"
                item-key="memberId"
                :headers="headers"
                :search="search">
                <template v-slot:top>
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
                                <span class="headline">Test</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <user-form-component :member="editedMember" />
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer />
                                <v-btn color="blue darken-1" text @click="closeDialog">Cancel</v-btn>
                                <v-btn color="blue darken-1" text @click="updateMember">Save</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="editUser(item)"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        small
                        @click="deleteItem(item)"
                    >
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-card-text>

    </v-card>
</template>

<script>
    import {MemberCollection} from "./MemberCollection";
    import UserFormComponent from "../UserFormComponent";
    import {MemberModel} from "./MemberModel";

    export default {
        name: "MemberTable",
        components: {UserFormComponent},
        props: {
            users: Array
        },

        data: () => {
            return {
                dialog: false,
                collection: [],
                editedMember: {},
                headers: [
                    {text: "Prénom", value: "firstName", sortable: true, filterable: true},
                    {text: "Nom", value: "lastName", sortable: true, filterable: true},
                    {text: "Email", value: "email"},
                    {text: "Téléphone", value: "phone"},
                    {text: "CIP", value: "cip"},
                    {text: "Facebook", value: "facebookLink"},
                    {text: "Rôle", value: "role", sortable: true},
                    {text: "Date de création", value: "created_at", sortable: true},
                    {text: 'Actions', value: 'action', sortable: false}
                ],
                search: ''
            }
        },

        methods: {
            closeDialog() {
                this.dialog = false;
                setTimeout(() => {
                    this.editedMember = Object.assign({}, {});
                    this.editedIndex = -1
                }, 300);
            },
            editUser(user) {
                console.log(user);
                this.editedIndex = this.users.indexOf(user);
                this.editedMember = Object.assign({}, user);
                this.dialog = true;
            },
            updateMember() {
                (new MemberModel(this.editedMember)).save().then(response => {
                    console.log(response);
                }).catch(error => {
                    console.log(error);
                });
            }
        },

        mounted() {
            console.log(this.users);
            console.log(new MemberCollection(this.users));
            this.collection = new MemberCollection(this.users);
        }
    }
</script>

<style scoped>

</style>
