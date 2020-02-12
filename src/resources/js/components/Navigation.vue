<template>
    <div>
        <v-navigation-drawer
            v-model="drawer"
            :mini-variant.sync="mini"
            permanent
            app
        >
            <v-list-item class="px-2">
                <v-list-item-avatar>
                    <v-img src="https://randomuser.me/api/portraits/men/85.jpg"></v-img>
                </v-list-item-avatar>

                <v-list-item-title>John Leider</v-list-item-title>

                <v-btn
                    icon
                    @click.stop="mini = !mini"
                >
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
            </v-list-item>


            <v-list>
                <div v-for="(link, i) in items">
                    <v-list-item
                        v-if="link.subLinks.length === 0"
                        link
                        :to="link.href"
                        :key="link.text">
                        <v-list-item-icon>
                            <v-icon>{{ link.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>{{ link.title}} </v-list-item-title>
                    </v-list-item>

                    <v-list-group
                        v-else
                        :prepend-icon="link.icon"
                        value="true">
                        <template v-slot:activator>
                            <v-list-item-title>{{ link.text }}</v-list-item-title>
                        </template>
                        <v-list-item
                            v-for="(subLink, i) in link.subLinks"
                            link
                            :to="subLink.href"
                            :key="i">
                            <v-list-item-title v-text="subLink.text" />
                            <v-list-item-icon>
                                <v-icon>{{ subLink.icon }}</v-icon>
                            </v-list-item-icon>
                        </v-list-item>
                    </v-list-group>
                </div>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar app color="indigo" dark>
            <v-app-bar-nav-icon @click.stop="mini = !mini"/>
            <v-toolbar-title>Club de plein air altitude</v-toolbar-title>
        </v-app-bar>
    </div>
</template>

<script>
    export default {
        name: "Navigation",

        data: () => {
            return {
                drawer: false,
                items: [
                    { title: 'Accueil', icon: 'mdi-home-city', href: '/', subLinks: [] },
                    {
                        title: 'Membres',
                        icon: 'mdi-account-group-outline',
                        subLinks: [{
                            text: 'Créer',
                            href: '/members/create',
                            icon: 'mdi-account-multiple-plus-outline'
                        }]
                    }
                ],
                mini: true
            }
        },
    }
</script>

<style scoped>

</style>
