import {Model} from 'vue-mc';
import {string, email, required} from "vue-mc/validation";

export class MemberModel extends Model {

    options() {
        return {
            identifier: 'member_id',
            methods: {
                update: 'PUT'
            }
        }
    };

    defaults() {
        return {
            memberId: null,
            email: null,
            firstName: null,
            lastName: null,
            password: null,
            facebookLink: null,
            cip: null,
            role: 'Membre',
            phone: null,
            phoneRegion: 'CA'
        }
    };

    availableRoles() {
        return ['Membre', 'Permanent', 'Admin'];
    };

    routes() {
        return {
            fetch: '/members/{memberId}',
            save: '/members',
            update: '/members/{memberId}',
            delete: '/members/{memberId}'
        }
    };

    update() {
        let url = this.getRoute('update').replace(/\{memberId\}/, this.memberId);

        axios.put(url, this.toJSON()).then(response => {
            return response.data;
        }).catch(error => {
            console.log(error);
        });
    }

    validation() {
        return {
            firstName: string.and(required),
            lastName: string.and(required),
            email: email,
        }
    };
}
