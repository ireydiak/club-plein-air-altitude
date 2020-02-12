@extends('layout.app')

@section('content')
    <v-container
        fill-height
        fluid
        grid-list-xl>
        <v-layout justify-center wrap>
            <v-flex xs12 md8>
                <v-card tile>
                    <v-card-title>Créer un membre</v-card-title>
                    <v-card-text>
                        <user-form-component
                            :member="null"
                            :button-text="'Créer'"
                            :method="'store'"
                            :title="'Ajouter un membre'"
                        ></user-form-component>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
@endsection
